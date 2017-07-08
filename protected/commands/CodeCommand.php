<?php

class CodeCommand extends CConsoleCommand
{

    public function actionCallUser()
    {
        $util = new UtilManager();
        $user = $util->getCallUser();

        echo date("Y-m-d H:i:s") . "\r\n";
        echo $user["name"]."--".$user["email"]."\r\n";

       if($user != null)
       {

       $cmd =  "/opt/lampp/bin/php /opt/lampp/htdocs/Future/protected/commands/Crons.php Code sendMail --email=".
               $user['email'].
               " --title=".
               $user['name'].",快递交周报".
               " --body=".
               "Funny!http://iautostock.com/report";
       system($cmd);        
       }
    }

    public  function  actionUpdateUnFinished()
    {
        $util = new UtilManager();
        $util->updateUnFinished();
    }
}
