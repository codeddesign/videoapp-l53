<?php

namespace App\Models;

use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Support\Str;

class Model extends BaseModel
{
    /**
     * The attributes that are encrypted.
     *
     * @var array
     */
    protected $encrypts = [];

    /**
     * Get a plain attribute (not a relationship).
     *
     * @param string  $key
     *
     * @return mixed
     */
    public function getAttributeValue($key)
    {
        $value = $this->getAttributeFromArray($key);

        // If the attribute is encrypted, decrypt its value for calling mutators
        // or casting.
        if (in_array($key, $this->encrypts)) {
            $value = $this->decrypt($value);
        }

        // If the attribute has a get mutator, we will call that then return what
        // it returns as the value, which is useful for transforming values on
        // retrieval from the model to a form that is more useful for usage.
        if ($this->hasGetMutator($key)) {
            return $this->mutateAttribute($key, $value);
        }

        // If the attribute exists within the cast array, we will convert it to
        // an appropriate native PHP type dependant upon the associated value
        // given with the key in the pair. Dayle made this comment line up.
        if ($this->hasCast($key)) {
            $value = $this->castAttribute($key, $value);
        }

        // If the attribute is listed as a date, we will convert it to a DateTime
        // instance on retrieval, which makes it quite convenient to work with
        // date fields without having to create a mutator for each property.
        elseif (in_array($key, $this->getDates())) {
            if (! is_null($value)) {
                return $this->asDateTime($value);
            }
        }

        return $value;
    }

    /**
     * Set a given attribute on the model.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return void
     */
    public function setAttribute($key, $value)
    {
        // First we will check for the presence of a mutator for the set operation
        // which simply lets the developers tweak the attribute as it is set on
        // the model, such as "json_encoding" an listing of data for storage.
        if ($this->hasSetMutator($key)) {
            $method = 'set'.Str::studly($key).'Attribute';

            return $this->{$method}($value);
        }

        // If an attribute is listed as a "date", we'll convert it from a DateTime
        // instance into a form proper for storage on the database tables using
        // the connection grammar's date format. We will auto set the values.
        elseif (in_array($key, $this->getDates()) && $value) {
            $value = $this->fromDateTime($value);
        }

        if ($this->isJsonCastable($key) && ! is_null($value)) {
            $value = json_encode($value);
        }

        // If the attribute is encrypted, encrypt it before setting the
        // attributes array.
        if (in_array($key, $this->encrypts)) {
            $value = $this->encrypt($value);
        }

        $this->attributes[$key] = $value;
    }

    /**
     * Encrypt a value.
     *
     * @param string $value
     *
     * @return string
     * @throws \Illuminate\Contracts\Encryption\EncryptException
     */
    protected function encrypt($value)
    {
        if (! is_null($value)) {
            $value = app(Encrypter::class)->encrypt($value);
        }

        return $value;
    }

    /**
     * Decrypt a value.
     *
     * @param string $value
     *
     * @return string
     * @throws \Illuminate\Contracts\Encryption\DecryptException
     */
    protected function decrypt($value)
    {
        if (! is_null($value)) {
            $value = app(Encrypter::class)->decrypt($value);
        }

        return $value;
    }
}
