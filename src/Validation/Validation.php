<?php

namespace App\Validation;

use App\Exceptions\ErrorCode;
use App\Exceptions\Exception;
use App\Exceptions\InvalidArgumentException;
use Phalcon\Messages\Messages;
use Phalcon\Mvc\Model;
use Phalcon\Validation\Validator\Alnum as AlNumValidator;
use Phalcon\Validation\Validator\Alpha as AlphaValidator;
use Phalcon\Validation\Validator\Between as BetweenValidator;
use Phalcon\Validation\Validator\Callback as CallbackValidator;
use Phalcon\Validation\Validator\Confirmation as ConfirmationValidator;
use Phalcon\Validation\Validator\CreditCard as CreditCardValidator;
use Phalcon\Validation\Validator\Digit as DigitValidator;
use Phalcon\Validation\Validator\Email as EmailValidator;
use Phalcon\Validation\Validator\ExclusionIn as ExclusionInValidator;
use Phalcon\Validation\Validator\Identical as IdenticalValidator;
use Phalcon\Validation\Validator\InclusionIn as InclusionInValidator;
use Phalcon\Validation\Validator\Ip as IpValidator;
use Phalcon\Validation\Validator\Numericality as NumericalValueValidator;
use Phalcon\Validation\Validator\PresenceOf as PresenceOfValidator;
use Phalcon\Validation\Validator\Regex as RegexValidator;
use Phalcon\Validation\Validator\Uniqueness as UniquenessValidator;
use Phalcon\Validation\Validator\Url as UrlValidator;

class Validation extends \Phalcon\Validation
{
    protected bool $throwError = false;
    protected bool $displayDetails = false;

    // todo 嵌套验证

    /**
     * @param $data
     * @param $entity
     * @param bool $throw
     * @param bool $displayDetails
     * @return Messages
     */
    public function validate($data = null, $entity = null, bool $throw = true, bool $displayDetails = false): Messages
    {
        $this->throwError($throw);
        $this->displayDetails($displayDetails);
        return parent::validate($data, $entity);
    }

    /**
     * @param bool $bool
     * @return void
     */
    public function throwError(bool $bool = true)
    {
        $this->throwError = $bool;
    }

    /**
     * @param bool $bool
     * @return void
     */
    public function displayDetails(bool $bool = true)
    {
        $this->displayDetails = $bool;
    }

    /**
     * @param $data
     * @param $entity
     * @param Messages $messages
     * @return void
     * @throws Exception
     */
    public function afterValidation($data, $entity, Messages $messages)
    {
        if ($this->throwError === true) {
            if ($this->displayDetails == true) {
                $exception = InvalidArgumentException::withCode(ErrorCode::INVALID_PARAMETER);
                $exception->setDetailMessages($messages);
            } else {
                $exception = new InvalidArgumentException(
                    $messages->current()->getMessage(),
                    ErrorCode::INVALID_PARAMETER
                );
            }
            throw $exception;
        }
    }

    /**
     * @param $field
     * @param array $options
     * @return $this
     */
    public function alNumRule($field, array $options = []): Validation
    {
        $validator = new AlNumValidator($options);
        $validator->setTemplate('字段 :field 必须只包含字母和数字');
        $this->add($field, $validator);

        return $this;
    }

    /**
     * @param $field
     * @param array $options
     * @return $this
     */
    public function alphaRule($field, array $options = []): Validation
    {
        $validator = new AlphaValidator($options);
        $validator->setTemplate('字段 :field 必须只包含字母');
        $this->add($field, $validator);

        return $this;
    }

    /**
     * @param $field
     * @param $minimum
     * @param $maximum
     * @param array $options
     * @return $this
     */
    public function betweenRule($field, $minimum, $maximum, array $options = []): Validation
    {
        $validator = new BetweenValidator($options);
        $validator->setTemplate('字段 :field 必须在 :min 到 :max 的范围内');
        $validator->setOption('minimum', $minimum);
        $validator->setOption('maximum', $maximum);
        $this->add($field, $validator);

        return $this;
    }

    /**
     * @param $field
     * @param callable $callable
     * @param array $options
     * @return $this
     */
    public function callbackRule($field, callable $callable, array $options = []): Validation
    {
        $validator = new CallbackValidator($options);
        $validator->setTemplate('字段 :field 必须匹配回调函数');
        $validator->setOption('callable', $callable);
        $this->add($field, $validator);

        return $this;
    }

    /**
     * @param $field
     * @param $withField
     * @param array $options
     * @return $this
     */
    public function confirmationRule($field, $withField, array $options = []): Validation
    {
        $validator = new ConfirmationValidator($options);
        $validator->setTemplate('字段 :field 必须与 :with 相同');
        $validator->setOption('with', $withField);
        $this->add($field, $validator);

        return $this;
    }

    /**
     * @param $field
     * @param array $options
     * @return $this
     */
    public function creditCardRule($field, array $options = []): Validation
    {
        $validator = new CreditCardValidator($options);
        $validator->setTemplate('字段 :field 对于信用卡号无效');
        $this->add($field, $validator);

        return $this;
    }

    /**
     * @param $field
     * @param string $format
     * @param array $options
     * @return $this
     */
    public function dateRule($field, string $format = 'Y-m-d', array $options = []): Validation
    {
        $validator = new ConfirmationValidator($options);
        $validator->setTemplate('字段 :field 不是有效日期');
        $validator->setOption('format', $format);
        $this->add($field, $validator);

        return $this;
    }

    /**
     * @param $field
     * @param array $options
     * @return $this
     */
    public function digitRule($field, array $options = []): Validation
    {
        $validator = new DigitValidator($options);
        $validator->setTemplate('字段 :field 必须是数字');
        $this->add($field, $validator);

        return $this;
    }

    /**
     * @param $field
     * @param array $options
     * @return $this
     */
    public function emailRule($field, array $options = []): Validation
    {
        $validator = new EmailValidator($options);
        $validator->setTemplate('字段 :field 必须是电子邮件地址');
        $this->add($field, $validator);

        return $this;
    }

    /**
     * @param $field
     * @param array $domain
     * @param array $options
     * @return $this
     */
    public function exclusionInRule($field, array $domain, array $options = []): Validation
    {
        $validator = new ExclusionInValidator($options);
        $validator->setTemplate('字段 :field 不能是列表 :domain 的一部分');
        $validator->setOption('domain', $domain);
        $this->add($field, $validator);

        return $this;
    }

    /**
     * @param string $field
     * @param array $options
     * @return $this
     */
    public function fileRule(string $field, array $options = []): Validation
    {
        $validator = new ExclusionInValidator($options);
        $this->add($field, $validator);

        return $this;
    }

    /**
     * @param $field
     * @param $accepted
     * @param array $options
     * @return $this
     */
    public function identicalRule($field, $accepted, array $options = []): Validation
    {
        $validator = new IdenticalValidator($options);
        $validator->setTemplate('字段 :field 没有预期值');
        $validator->setOption('accepted', $accepted);
        $this->add($field, $validator);

        return $this;
    }

    /**
     * @param $field
     * @param $domain
     * @param bool $strict
     * @param array $options
     * @return $this
     */
    public function inclusionInRule($field, $domain, bool $strict = false, array $options = []): Validation
    {
        $validator = new InclusionInValidator($options);
        $validator->setTemplate('字段 :field 字段必须是列表 :domain 的一部分');
        $validator->setOption('domain', $domain);
        $validator->setOption('strict', $strict);
        $this->add($field, $validator);

        return $this;
    }

    /**
     * @param string $field
     * @param array $options
     * @return $this
     */
    public function ipRule(string $field, array $options = []): Validation
    {
        $validator = new IpValidator($options);
        $validator->setTemplate('字段 :field 必须是有效的 IP 地址');
        $this->add($field, $validator);

        return $this;
    }

    /**
     * @param string $field
     * @param array $options
     * @return $this
     */
    public function NumericalValueRule(string $field, array $options = []): Validation
    {
        $validator = new NumericalValueValidator($options);
        $validator->setTemplate('字段 :field 必须是有效的数字格式');
        $this->add($field, $validator);

        return $this;
    }

    /**
     * @param string $field
     * @param array $options
     * @return $this
     */
    public function presenceOfValueRule(string $field, array $options = []): Validation
    {
        $validator = new PresenceOfValidator($options);
        $validator->setTemplate('字段 :field 是必需的');
        $this->add($field, $validator);

        return $this;
    }

    /**
     * @param $field
     * @param $pattern
     * @param array $options
     * @return $this
     */
    public function regexRule($field, $pattern, array $options = []): Validation
    {
        $validator = new RegexValidator($options);
        $validator->setTemplate('字段 :field 与所需格式不匹配');
        $validator->setOption('pattern', $pattern);
        $this->add($field, $validator);

        return $this;
    }

    /**
     * @param $field
     * @param $max
     * @param $min
     * @param array $options
     * @return $this
     */
    public function stringRule($field, $max, $min, array $options = []): Validation
    {
        if (empty($options)) {
            $options = [
                'max' => $max,
                'min' => $min,
                'messageMaximum' => '字段 :field 不得超过 :max 个字符长度',
                'messageMinimum' => '字段 :field 的长度必须至少为 :min 个字符',
            ];
        }

        $this->add($field, new RegexValidator($options));

        return $this;
    }

    /**
     * @param $field
     * @param Model $model
     * @param array|null $except
     * @param callable|null $convert
     * @param array $options
     * @return $this
     */
    public function uniquenessRule(
        $field,
        Model $model,
        ?array $except = null,
        ?callable $convert = null,
        array $options = []
    ): Validation {
        $validator = new UniquenessValidator($options);
        $validator->setTemplate('字段 :field 必须是唯一的');
        $validator->setOption('convert', $convert);
        $validator->setOption('model', $model);
        $validator->setOption('except', $except);
        $this->add($field, $validator);

        return $this;
    }

    /**
     * @param $field
     * @param array $options
     * @return $this
     */
    public function urlRule($field, array $options = []): Validation
    {
        $validator = new UrlValidator($options);
        $validator->setTemplate('字段 :field 必须是 url');
        $this->add($field, $validator);

        return $this;
    }
}
