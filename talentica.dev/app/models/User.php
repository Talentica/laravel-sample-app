<?php

use Illuminate\Auth\UserInterface;

class User extends Eloquent implements UserInterface
{
	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'api_key');

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = array('username', 'password');

	/**
	 * Lists relationship.
	 *
	 * @return Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function tasklists()
	{
		return $this->hasMany('TaskList');
	}

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Password mutator.
	 *
	 * @param  string  $password
	 * @return void
	 */
	public function setPasswordAttribute($password)
	{
		$this->attributes['password'] = Hash::make($password);
	}

	/**
	 * Generate a random, unique API key.
	 *
	 * @return string
	 */
	public static function createApiKey()
	{
		return Str::random(32);
	}


    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        return $this->token;
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string $value
     * @return void
     */
    public function setRememberToken($value)
    {
        $this->token = $token;
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */  
    public function getRememberTokenName()
    {
        return $this->token;

    }

    public function setRememberTokenName($token)
    {
        $this->token = $token;
    }
}