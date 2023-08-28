<?php

namespace App\Models\Traits;

trait LogBase
{
    /**
     * 父类model文件
     * @var string
     */
    protected string $parentClass = '';

    /**
     * 父类model记录字段
     * @var string
     */
    protected string $parentId = 'id';

    /**
     * 业务名字
     * @var string
     */
    protected string $businessName = '日志';

    /**
     * 记录字段
     * @var string
     */
    protected string $logField = 'id';

    /**
     * 日志记录字段
     * @var array
     */
    protected array $logFields = [];

    /**
     * @var array|string[]
     */
    private static array $actions = ['created' => '添加', 'updated' => '修改', 'deleted' => '删除'];

    /**
     * 记录日志标识
     * @var bool
     */
    protected static $logIdentification = false;

    /**
     * @return bool | void
     */
    public static function boot()
    {
        parent::boot();
        //记录日志判断
        if (!static::$logIdentification) {
            return true;
        }
        //登录用户信息拉取
        $user = auth('api')->user();
        //监听更新
        self::updated(function (self $model) use ($user) {
            self::saveLog($model, $user, 'updated');
        });
        //监听创建
        self::created(function (self $model) use ($user) {
            self::saveLog($model, $user, 'created');
        });
        //监听删除
        self::deleted(function (self $model) use ($user) {
            self::saveLog($model, $user, 'deleted');
        });
    }

    /**
     * @param $model
     * @param $user
     * @param $action
     */
    public static function saveLog($model, $user, $action)
    {
        $attributeLabels = $model->logFields;
        $desc = [];

        if (!$user) {
            // 没登录，取当前操作表的 user_id、 user_name
            $user = (object)[
                'id'   => $model->id ?? 0,
                'name' => $model->username ?? '系统',
            ];
        }
        switch ($action) {
            case 'updated':
                foreach ($attributeLabels as $attribute => $label) {
                    if ($model->isDirty($attribute)) {// 判断是否修改
                        $old = $model->getOriginal($attribute); // 原始值
                        if (!empty($model->amountFields) && in_array($attribute, $model->amountFields)) {
                            $old = $old / 10000;
                        }
                        $new = $model->$attribute; // 新值
                        if (is_array($old)) {
                            $old = json_encode($old, JSON_UNESCAPED_UNICODE);
                        }
                        if (is_array($new)) {
                            $new = json_encode($new, JSON_UNESCAPED_UNICODE);
                        }
                        $remark = "{$label} 由 {$old} 改为 {$new}";
                        $attributeStatus = strtoupper($attribute) . 'S';
                        $attributesValue = $model->getEnumsValue($attributeStatus);
                        if (!empty($attributesValue)) {
                            $remark = "{$label} 由 " . $attributesValue[$old] . " 改为 " . $attributesValue[$new];
                        }
                        $desc[] = $remark;
                    }
                }
                break;
            case 'created' && !empty($model->businessName):
                $desc = ['添加：' . $model->businessName . 'id:' . $model->{$model->logField}];
                break;
            case 'deleted' && !empty($model->businessName):
                $desc = ['删除：' . $model->businessName . 'id:' . $model->{$model->logField}];
                break;
        }

        $logData = [
            'action'    => $action,
            'title'     => self::$actions[$action],
            'desc'      => count($desc) ? implode(";\r\n", $desc) : '',
            'user_id'   => $user ? $user->id : 0,
            'user_name' => $user ? $user->username : '系统',
        ];

        if ($model->parentClass) {
            $logData['logable_id'] = $model->{$model->parentId};
            $logData['logable_type'] = $model->parentClass;
        }

        $model->log()->createMany([$logData]);
    }

    /**
     * @param $attributeStatus
     * @return mixed|string
     */
    public function getEnumsValue($attributeStatus)
    {
        try {
            return constant('static::' . $attributeStatus);
        } catch (\Throwable $e) {
            return "";
        }
    }

    /**
     * @param string $action
     * @param string $desc
     * @return array
     */
    public function logArray(string $action, string $desc = ''): array
    {
        $user = auth('api')->user();
        if (str_contains($desc, 'userId')) {
            $desc = str_replace('userId', "账号id:" . ($user->id ?? 0), $desc);
        }
        return [
            'user_id'               => $user->id ?? 0,
            'user_name'             => $user->username ?? '系统',
            'action'                => $action,
            'title'                 => self::$actions[$action] ?? '',
            'created_at'            => getCurrentTime(),
            'desc'                  => $desc,
            'logable_id'            => $this->id ?? 0,
            'logable_type'          => get_class($this),
            'logable_children_id'   => 0,
            'logable_children_type' => '',
        ];
    }

    /**
     * 默认日志的方法|有需要可重写
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    protected function log()
    {
        return $this->morphMany('App\Models\Log\Log', 'logable', null, 'logable_id', 'id');
    }
}
