<?php
class UtilManager
{

    public  function  updateUnFinished()
    {
        User::model()->updateAll(array('finished'=>0),'finished=:finished',array(':finished'=>1));
    }

    public function getCallUser()
    {
         $unfinishedUsers = self::getAllUsers(0);
         if(count($unfinishedUsers) == 0)
         {
            return null;
         }
         else
         {
           $list = User::model()->findAllBySql("SELECT id,name,email FROM  `user`  WHERE  finished = 0 and called = 0 order by id ASC limit 0,1");

              if(count($list) == 1)
              {
                 User::model()->updateAll(array('called'=>1),'id=:id',array(':id'=>$list[0]["id"]));
                 return $list[0];
              }
              else
              {
                 User::model()->updateAll(array('called'=>0),'finished=:finished',array(':finished'=>0));

                   $list = User::model()->findAllBySql("SELECT id,name,email FROM  `user`  WHERE  finished = 0 and called = 0 order by id ASC limit 0,1");

                   User::model()->updateAll(array('called'=>1),'id=:id',array(':id'=>$list[0]["id"]));
                   return $list[0];



              }
         }




    }
    public function getWeekRange()
   {
      $weekNum = date('w');
      $start = 1-$weekNum;
      $end = 5-$weekNum;
      $curDay = date("Y-m-d");
      $startDay = date('m.d',strtotime("$curDay +$start day"));
      $endDay = date('m.d',strtotime("$curDay +$end day"));
      return array("start"=>$startDay,"end"=>$endDay);  
   }
    public function  getWeekId()
    {
        $week = date('W');
       
        return $week;
    }
    public function getReportByWeek($weekId=-1)
    {

         if($weekId == -1) 
          {
           $weekId = self::getWeekId();
          }

         $list = Report::model()->findAllBySql("SELECT p.id, r.detail, r.user_id FROM  `report` AS r, project AS p WHERE r.week_id = :weekId AND r.project_id = p.id ORDER BY p.id ASC , r.id ASC ",array(':weekId'=>$weekId));   
         return $list;
    }
    public function getAllOwners()
    {
        $criteria=new CDbCriteria;
        $criteria->select='id,name,email,mobile'; // only select the 'title' column
        $criteria->condition='id > 0';
        $criteria->order = 'id asc';
        $ownerArray=Owner::model()->findAll($criteria);

        return $ownerArray;
    }

    public function getAllUsers($finished = -1)
    {
        $criteria=new CDbCriteria;
        $criteria->select='id,name,email,mobile'; // only select the 'title' column
        if($finished == -1)
        $criteria->condition='id > 0';
        else
            $criteria->condition='finished = :finished';
        $criteria->params=array(':finished'=>$finished);
        $criteria->order = 'id asc';
        $userArray=User::model()->findAll($criteria);

        return $userArray;
    }

    public function getAllProjects($active = -1)
    {
        $criteria=new CDbCriteria;
        $criteria->select='id,name,jira,plan,owner_id,active'; // only select the 'title' column
        if($active == -1)
            $criteria->condition='id > 0';
        else
            $criteria->condition='active = :active';
        $criteria->params=array(':active'=>$active);
        $criteria->order = 'active desc,id desc';
        $projArray=Project::model()->findAll($criteria);

        return $projArray;
    } 

    public function getProj($id)
    {
        $project = Project::model()->findByPk($id);
        return $project;
    }

    public function getProjByJira($jira)
    {
        $item = Project::model()->findByAttributes(array('jira' => $jira));

        if($item)
        {
            return $item;
        }
        else{
            return null;
        }
    }

    public  function  addProj($name,$jira,$plan, $owner_id)
    {

        $project = new Project();
        $project->name = $name;
        $project->jira = $jira;
        $project->owner_id = $owner_id;
        $project->create_time = date("Y-m-d H:i:s");
        $project->plan = $plan;
        $project->active = 1;
        $project->save();
        return $project;

    }

    public  function  addReport($user,$proj,$detail)
    {

        $report = new Report();
        $report->user_id = $user;
        $report->project_id = $proj;
        $report->detail = $detail;
        $report->create_time = date("Y-m-d H:i:s");
        $report->week_id = self::getWeekId();
        $valid = $report->save();
        return $valid;

    }

    public  function  updateUser($user,$finish)
    {
        $item = User::model()->findByAttributes(array('id' => $user));

        if($item)
        {
            $item->finished = $finish;
        }
        else{
            return false;
        }

        $valid = $item->save();
        return $valid;

    }

}
