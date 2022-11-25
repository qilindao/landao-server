<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use JoyceZ\LaravelLib\Validation\Validator as CustomValidator;

/**
 * 自定义表单扩展验证规则
 *
 * TODO：小写扩展 rule，否则无效
 *
 * Trait ValidatorTrait
 *
 * @author joyecZhang <zhangwei762@163.com>
 * @package App\Traits
 */
trait ValidatorTrait
{
    public function validatorBoot()
    {
        //验证手机号
        Validator::extend('mobile', function ($attribute, $value, $parameters, $validator) {
            $pattern = '/^1[3456789]{1}\d{9}$/';
            $res = preg_match($pattern, $value);
            return $res > 0;
        });
        Validator::replacer('mobile', function ($message, $attribute, $rule, $parameters) {
            return $message;
        });
        //中文名
        Validator::extend('chinese_name', function ($attribute, $value, $parameters, $validator) {
            return CustomValidator::isChineseName($value);
        });
        Validator::replacer('chinese_name', function ($message, $attribute, $rule, $parameters) {
            return $message;
        });
        //密码必须至少包含8个字符、至少含有一个数字、小写和大写字母以及特殊字符
        Validator::extend('complex_pwd', function ($attribute, $value, $parameters, $validator) {
            return 0 < preg_match("/^.*(?=.{8,})(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=])[a-zA-Z0-9@#$%^&+=]*$/", $value);
        });
        Validator::replacer('complex_pwd', function ($message, $attribute, $rule, $parameters) {
            return $message;
        });
        //验证是否是非负数、非小数点数字。用于 数字 ID 验证
        Validator::extend('positive_id', function ($attribute, $value, $parameters, $validator) {
            return CustomValidator::isPositive($value);
        });
        Validator::replacer('positive_id', function ($message, $attribute, $rule, $parameters) {
            return $message;
        });
        //验证身份证号
        Validator::extend('identity', function ($attribute, $value, $parameters, $validator) {
            return $this->checkIdentityCard($value);
        });
        Validator::replacer('identity', function ($message, $attribute, $rule, $parameters) {
            return $message;
        });
        /**
         * 验证结束时间段大于开始时间段
         *
         * 使用方式：'period_end' => 'required|date_format:"H:i"|gt_time:"H:i",period_start',
         *
         * gt_period_time:"H:i",period_start，第一个为时间格式，第二个为要比对的字段名
         *
         */
        Validator::extend('gt_time', function ($attribute, $value, $parameters, $validator) {
            return Carbon::createFromFormat($parameters[0], $value)->gt(Carbon::createFromFormat($parameters[0], request($parameters[1])));
        });
        Validator::replacer('gt_time', function ($message, $attribute, $rule, $parameters) {
            return $message;
        });
        //校验数组至少填写一项
        Validator::extend('min_array', function ($attribute, $value, $parameters, $validator) {
            return count(array_filter($value, function ($var) use ($parameters) {
                return ($var && $var >= $parameters[0]);
            }));
        });
    }

    /**
     * 验证身份证
     * @param $idCard
     * @return bool
     * @author centphp.com
     * @date 2020/5/1
     */
    public function checkIdentityCard($idCard)
    {
        // 只能是18位
        if (strlen($idCard) != 18) {
            return false;
        }
        // 取出本体码
        $idcard_base = substr($idCard, 0, 17);
        // 取出校验码
        $verify_code = substr($idCard, 17, 1);
        // 加权因子
        $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
        // 校验码对应值
        $verify_code_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
        // 根据前17位计算校验码
        $total = 0;
        for ($i = 0; $i < 17; $i++) {
            $total += substr($idcard_base, $i, 1) * $factor[$i];
        }
        // 取模
        $mod = $total % 11;
        // 比较校验码
        if ($verify_code == $verify_code_list[$mod]) {
            return true;
        } else {
            return false;
        }
    }

}
