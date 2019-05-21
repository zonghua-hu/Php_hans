<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/21
 * Time: 10:35
 */

class TradeClient
{
    public function main()
    {
        $card = self::initCard();
        fwrite(STDOUT,"-----初始化卡信息----");
        self::showCard($card);
        $flag = true;

        while($flag) {
            $trade = self::createTrade();
            DeductionFacade::deduct($card,$trade);
            fwrite(STDOUT,"-----交易凭证~----");
            fwrite(STDOUT,$trade->setTradeNo($trade)."交易成功");
            fwrite(STDOUT,"交易金额".$trade->getAmount());
            $this->showCard($card);
            fwrite(STDOUT,"是否退出？");
            if (fgets(STDIN) == "Y") {
                $flag = false;
            }
        }
    }

    /**
     * 初始化卡信息
     * @return card
     */
    private function initCard()
    {
        $card = new Card();
        $card->setCardNo("88888888");
        $card->setFreeMoney(888888);
        $card->setSteadyMoney(99999);
        return $card;
    }

    /**
     * 生成交易
     * @return Trade
     */
    private function createTrade()
    {
        $trade = new Trade();
        fwrite(STDOUT,"请输入交易编号：");
        $trade->setTradeNo(trim(fgets(STDIN)));
        fwrite(STDOUT,"请输入交易金额：");
        $trade->setAmount(trim(fgets(STDIN)));
        return $trade;
    }

    /**
     * 展示卡余额信息
     * @param Card $card
     * @return string
     */
    private function showCard(Card $card)
    {
        $str_card  = "";
        $str_card .= "卡号为：".$card->getCardNo();
        $str_card .= "固定金额为：".$card->getSteadyMoney()/100.00;
        $str_card .= "自由金额为：".$card->getFreeMoney()/100.00;
        return $str_card;
    }



}