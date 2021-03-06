<?php


namespace Casper\Fields;


use Casper\Exceptions\ValidationFailedException;

class EmailField extends Fields
{
    private const emailErrorMessage = 'Invalid email address';
    /**
     * @param string $name
     * @return string
     */
    public function asHtml(string $name=''): string
    {
        $res = parent::asHtml($name);
        $res = str_replace("type='text'", "type='email'", $res);
        return $res;
    }

    public function validate(): ?string
    {
        try {
            $this->checkRequired($this->data);
            $this->checkNull($this->data);
            $this->checkBlank($this->data);
            $this->checkEmpty($this->data);

            if(!empty($this->data)){
                if(isset($this->regex)){
                    $this->checkRegex($this->regex, $this->data);
                }
                if (!filter_var($this->data, FILTER_VALIDATE_EMAIL, FILTER_FLAG_EMAIL_UNICODE)) {
                    throw new ValidationFailedException(self::emailErrorMessage);
                }
                $this->setCleanedData((string) $this->data);
            }

            $this->isValid = true;
            return null;

        }catch (ValidationFailedException $validationFailedException){
            $this->isValid = false;
            $this->setValidationErrorMessage($validationFailedException->getMessage());
            return $validationFailedException->getMessage();
        }
    }

}