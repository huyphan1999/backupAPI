<?php

namespace App\Api\Repositories\Contracts;

use App\Api\Entities\User;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface UserRepository.
 */
interface UserRepository extends RepositoryInterface
{
	public function getListUser($params = [], $limit = 0);
	public function getRootUser($params = [], $limit = 0);
	public function sendMailAlertAdmin($params);
	public function sendMailUsingAccountDemo($params);
	public function sendMailActiveAccount($params);
	public function sendMailAutomaticHrm($params);
	public function sendMailRemindCustomer($params);
	public function sendMailTrial30Days($params);
	public function sendMailHowToUse($params);
	public function sendMailFromThanhNhan($params);
}
