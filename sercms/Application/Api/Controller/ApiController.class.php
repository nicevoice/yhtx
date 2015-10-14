<?php
/**
 * Created by PhpStorm.
 * User: lvxin
 * Date: 2015/9/16
 * Time: 10:10
 */

namespace Api\Controller;


use Think\Controller;

class ApiController extends Controller
{
    const SUCCESS_CODE = 1;
    const ERROR_CODE = 0;
    const EMPTY_DATA_CODE = 100001;
    /**
     * 成功返回值
     * @param Array $data 返回数据
    */
    protected function success($data){
        $response['code'] = self::SUCCESS_CODE;
        $response['data'] = $data;
        $response['msg'] = '';
        $response['time_stamp'] = time();
        $this->response($response);
    }

    /**
     * 错误返回值
     * @param Integer $errno 错误码
    */
    protected function error($errno = self::ERROR_CODE){
        $response['code'] = $errno;
        $response['data'] = '';
        $response['msg'] = $this->getErrorMsgByCode($errno);
        $response['time_stamp'] = time();
        $this->response($response);
    }

    /**
     * 返回值
     * @param Array $array 返回数据
    */
    protected function response($array){
        echo json_encode($array);
        exit;
    }
    /**
     * 错误码对应错误信息
    */
    private function getErrorMsgByCode($code){
        $array = array(
            0 => '未知异常',
        );
        return $array[$code];
    }
    /**
     * 数据加密
     * @param String $var
     * @return String
    */
    protected function encryption($var){
        $config = C('config');
        return base64_encode(AES::encode($config['AES_KEY'],$var));
    }
    /**
     * 数据解密
     * @param String $var
     * @return String
    */
    protected function decrypt($var){
        $config = C('config');
        return AES::decode($config['AES_KEY'],base64_decode($var));
    }
}

/**
 * 利用mcrypt做AES加密解密
 */

abstract class AES{
    /**
     * 算法,另外还有192和256两种长度
     */
    const CIPHER = MCRYPT_RIJNDAEL_128;
    /**
     * 模式
     */
    const MODE = MCRYPT_MODE_ECB;

    /**
     * 加密
     * @param String $key 密钥
     * @param String $str 需加密的字符串
     * @return String
     */
    static public function encode( $key, $str ){
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(self::CIPHER,self::MODE),MCRYPT_RAND);
        return mcrypt_encrypt(self::CIPHER, $key, $str, self::MODE, $iv);
    }

    /**
     * 解密
     * @param String $key 密钥
     * @param String $str 需解密的字符串
     * @return String
     */
    static public function decode( $key, $str ){
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(self::CIPHER,self::MODE),MCRYPT_RAND);
        return mcrypt_decrypt(self::CIPHER, $key, $str, self::MODE, $iv);
    }
}
