<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public $layout=false;

	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha' => array(
				'class' => 'CCaptchaAction',
				'backColor' => 0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page' => array(
				'class' => 'CViewAction',
			),
		);
	}


	public  function  actionAddReport($user,$proj,$detail)
	{

		$util = new UtilManager();
		$valid =  $util->addReport($user,$proj,$detail);

		$util->updateUser($user,1);
                $project = $util->getProj($proj);

                $keys = array();
             //   $keys[] = $project->name;
                $keys[] = $project->jira;
                $keys[] = $project->plan;
              // $owners = $util->getAllOwners();
              // $owner = self::getItem($owners,$project["owner_id"]);
               // $keys[] = $owner->name;
		//echo implode("---",$keys);
                echo "1111";
              

	}

	public  function  actionAddProject($name,$jira,$plan,$owner_id)
	{
		$util = new UtilManager();

		$item = $util->getProjByJira($jira);
		if($item == null)
		{
			$project =  $util->addProj($name,$jira,$plan,$owner_id);
			echo $project->id;
		}
		else
		{
			echo "0";
		}

	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */

        private function getItem($items,$id)
       {
           foreach($items as $item)
           {

              if($item->id == $id)
              {
                return $item;
              }
            }
            return null;

       }

        private function getProjectList()
        {
           $util = new UtilManager();
                $users = $util->getAllUsers();
                $projs = $util->getAllProjects();
                $owners = $util->getAllOwners();
                $reports = $util->getReportByWeek();

                $returnList =  array();

                $projectId = -1;
                $project = array();
                $pUsers = array();

			   $index = 1;
                foreach($reports as $report)
                 {
                   $user = self::getItem($users,$report["user_id"]);
                   if($report["id"] != $projectId)
                   {
					   $index = 1;

                     if($projectId != -1)
                      {
                          $project["users"] = implode(",",$pUsers);
                           $returnList[] = $project;
                      }

                     $projectId = $report["id"];
                     $project = array();
                     $pUsers = array();
                     $proj = self::getItem($projs,$projectId);
                     $project["name"] = $proj["name"];
                     $project["jira"] = $proj["jira"];
                     $project["plan"] = $proj["plan"];
                     $owner = self::getItem($owners,$proj["owner_id"]);
                     $project["owner"] = $owner["name"];
                     $project["details"] = $index."、".$report["detail"];
                     $pUsers[] = $user["name"];
                 }
                 else
                 {
					 $index ++;
                    $project["details"] .= "<br/>".$index."、".$report["detail"];

                     if(!in_array($user["name"],$pUsers))
                    $pUsers[] = $user["name"];
                  }

                }
               if($projectId != -1)
                 {
                    $project["users"] = implode(",",$pUsers);
                     $returnList[] = $project;
                  }


              return $returnList;

        }

	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
	        echo "Closed!";
                return;
                $util = new UtilManager();
                $weekId = $util->getWeekId();
                $weekRange = $util->getWeekRange();
                $users = $util->getAllUsers();
                $projs = $util->getAllProjects(1);
                $owners = $util->getAllOwners();
                $returnList = self::getProjectList();
                $unfinished = $util->getAllUsers(0);
                $unfinishedNames = array();
                foreach($unfinished as $item)
                 $unfinishedNames[] = $item->name;
		$this->render('index',array(
			'users'=>$users,
			'projs'=>$projs,
			'owners'=>$owners,
                        'reports'=>$returnList,
                        'unfinished'=>$unfinishedNames,
                        'weekId'=>$weekId,
                        'weekRange'=>$weekRange,
			));



	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-Type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}
