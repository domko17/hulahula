<?php

namespace App;

use App\Models\Banner;
use App\Models\ChatGroup;
use App\Models\ChatGroupMember;
use App\Models\Language;
use App\Models\Message;
use App\Models\PackageOrder;
use App\Models\StarOrder;
use App\Models\SurveyAnswer;
use App\Models\SurveyQuestion;
use App\Models\User\Profile;
use App\Models\UserPackage;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laratrust\Traits\LaratrustUserTrait;
use NotificationChannels\WebPush\HasPushSubscriptions;

/**
 * @inheritDoc
 * Class User
 * @package App
 *
 * @property UserPackage currentPackage
 * @property Collection studying
 * @property Collection teaching
 * @property Profile profile
 *
 * @property-read int $id
 *
 */
class User extends Authenticatable {
	use LaratrustUserTrait;
	use Notifiable;
	use HasPushSubscriptions;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'email', 'password',
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
		'last_activity' => 'datetime',
	];

	//static
	public static function birthdayWeek() {
		$now = Carbon::now();

		$query = Profile::where('birthday', "LIKE", "____-" . $now->format("m-d"));
		for ($i = 1; $i < 7; $i++) {
			$query->orWhere('birthday', "LIKE", "____-" . $now->addDays(1)->format("m-d"));
		}

		return $query->orderBy('birthday')->get();
	}

	public static function teachers() {
		return DB::table('users as u')
			->join('role_user as ru', 'ru.user_id', "=", 'u.id')
			->where('ru.role_id', "=", 2)
			->where('active', 1)
			->get();
	}

	public static function students() {
		return DB::table('users as u')
			->join('role_user as ru', 'ru.user_id', "=", 'u.id')
			->where('ru.role_id', "=", 3)
			->where('active', 1)
			->get();
	}

	//functions

	public function chatWith() {

		$send_to = Message::where('sender_id', $this->id)->distinct('reciever_id')->pluck('reciever_id');
		$recieve_from = Message::where('reciever_id', $this->id)->distinct('sender_id')->pluck("sender_id");

		$res = $send_to->union($recieve_from);

		return User::whereIn('id', $res)->get();
	}

	public function chatGroups() {
		$groups = ChatGroupMember::where('user_id', Auth::id())->pluck('group_id');

		return ChatGroup::whereIn('id', $groups)->get();
	}

	public function allMessagesWith($id) {
		$itsame = $this->id;
		$other = $id;
		$res = Message::where(function ($query) use ($other, $itsame) {
			$query->where('sender_id', $other)->where('reciever_id', $itsame);
		})->orWhere(function ($query) use ($other, $itsame) {
			$query->where('sender_id', $itsame)->where('reciever_id', $other);
		})->orderByDesc('created_at')
			->get();

		foreach ($res as $m) {
			if ($m->reciever_id == $itsame and !$m->is_read()) {
				$m->set_read();
			}
		}

		return $res;
	}

	/**
	 * @return int | 1 - online , 2 - idle , 0 - offline (long inactive), 3 - active within one hour
	 */
	public function is_online() {
		$now = Carbon::now();
		$last_active = $this->last_activity;

		if ($last_active->addMinutes(5) > $now) {
			return 1;
		}

		if ($last_active->addMinutes(5) > $now) {
			return 2;
		}

		if ($last_active->addMinutes(50) > $now) {
			return 3;
		}

		return 0;
	}

	public function getNewMessages() {
		$tmp = Message::where('reciever_id', $this->id)->where('read', 0)->orderByDesc('created_at')->pluck("sender_id");
		$res = [];
		foreach ($tmp->unique() as $i) {
			$res[] = $this->lastMessageWith($i);
		}
		return $res;
	}

	public function lastMessageWith($id) {
		$m1 = Message::where('sender_id', $id)->where('reciever_id', $this->id)->orderByDesc('created_at')->first();
		$m2 = Message::where('sender_id', $this->id)->where('reciever_id', $id)->orderByDesc('created_at')->first();

		if (!$m1 and !$m2) {
			$m = new Message();
			$m->message = "ERROR";
			return $m;
		}

		if (!$m1) {
			return $m2;
		}

		if (!$m2) {
			return $m1;
		}

		return $m1->created_at > $m2->created_at ? $m1 : $m2;
	}

	public function studyLevelOfLanguage($id) {
		$res = DB::table('student')
			->where('user_id', $this->id)
			->where('language_id', $id)
			->pluck('level')
			->first();

		return $res;
	}

	public function studyLevelOfLanguage_text($id) {
		$res = DB::table('student')
			->where('user_id', $this->id)
			->where('language_id', $id)
			->pluck('level')
			->first();

		switch ($res) {
		case "1":
			return "A1";
		case "2":
			return "A2";
		case "3":
			return "B1";
		case "4":
			return "B2";
		case "5":
			return "C1";
		default:
			return "ERR";
		}
	}

	public function availableQuickSurvey() {
		$tmp = SurveyAnswer::where('user_id', $this->id)
			->where('created_at', ">=", Carbon::now()->startOfDay())
			->where('created_at', "<", Carbon::now()->addDay()->startOfDay())
			->first();

		//if already answered today dont show another quick survey
		if ($tmp) {
			return null;
		}

		$answered_questions = SurveyAnswer::where('user_id', $this->id)->pluck('question_id');

		$query = SurveyQuestion::whereNotIn('id', $answered_questions);

		if ($this->hasRole('student')) {
			$query->where("students", 1);
		}
		if ($this->hasRole('teachet')) {
			$query->where("teachers", 1);
		}

		$question = $query->first();

		return $question;
	}


    public function canEnrollClass(){
	    $current_package = $this->currentPackage()->first();

	    if(!$current_package) return false; //Nemá aktívny balíček
	    if(!$current_package->classes_left) return false; //Nema uz volne hodiny v balicku

	    return true; //môže sa prihlásiť na hodinu
    }

	//Relations
	//------------------------------

	public function getMyBanners() {
		$banners_active = Banner::where('active', 1)->get();

		$res = [];

		foreach ($banners_active as $b) {
			if ($this->canSeeBanner($b)) {
				$res[] = $b;
			}

		}

		return $res;
	}

	private function canSeeBanner(Banner $b) {
		$v = $b->visibility;

		if ($v->type == 1) {
			return true;
		}

		if ($v->type == 2) {
			if ($v->guests and $this->hasRole('guest')) {
				return true;
			}

			$l = json_decode($v->language);
			if ($v->students and $this->hasRole('student')) {
				if (!$l) {
					return true;
				}

				if ($this->studying()->whereIn('languages.id', $l)->first()) {
					return true;
				}

				return false;
			}
			if ($v->teachers and $this->hasRole('teacher')) {
				if (!$l) {
					return true;
				}

				if ($this->teaching()->whereIn('languages.id', $l)->first()) {
					return true;
				}

				return false;
			}
		}
		if ($v->type == 3) {
			$user_arr = json_decode($v->user_id);
			return in_array($this->id, $user_arr);
		}
		return false;
	}

	public function studying() {
		return $this->belongsToMany('App\Models\Language', 'student', 'user_id');
	}

	public function teaching() {
		return $this->belongsToMany('App\Models\Language', 'teacher', 'user_id');
	}

	public function profile() {
		return $this->hasOne("App\Models\User\Profile", "user_id", "id");
	}

	public function roles() {
		return $this->belongsToMany('App\Role', 'role_user');
	}

	public function starOrders() {
		return $this->hasMany(StarOrder::class, "user_id", "id")->orderByDesc('id');
	}

	public function packageOrders() {
		return $this->hasMany(PackageOrder::class, "user_id", "id")->orderByDesc('id');
	}

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function currentPackage(){
	    return $this->hasOne(UserPackage::class, 'user_id', 'id')->whereIn('state', [1,2]);
    }

    public function packagesHistory(){
        return $this->hasMany(UserPackage::class, 'user_id', 'id');
    }

    public function is_student(){
        return $this->hasRole('student');
    }

    public function is_teacher(){
        return $this->hasRole('teacher');
    }

    public function is_admin(){
        return $this->hasRole('admin');
    }

}
