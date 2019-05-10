<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/9
 * Time: 21:05
 */

while(1) {
    fwrite(STDOUT,"λ ");
    $name = trim(fgets(STDIN));
    if (empty($name)) {
        fwrite(STDOUT,"");
    } else {

//
//        if (strlen($name) < 6) {
//            fwrite(STDOUT,"用户名不得少于6个字符：");
//        } elseif (strlen($name) > 11) {
//            fwrite(STDOUT,"用户名不得大于10个字符：");
//        }
//        fwrite(STDOUT,"请输入密码：");
//        $pwd = trim(fgets(STDIN));
//        if (strlen($pwd) < 8) {
//            fwrite(STDOUT,"密码不得少于8个字符：");
//        } elseif (strlen($pwd) > 13) {
//            fwrite(STDOUT,"密码不得大于12个字符：");
//        }
    }

}
