<!DOCTYPE html>
<html ng-app='myApp'>
    <head>
        <meta charset="utf-8">
        <title>DashBoard</title>

        <!-- Link to font and bootstrap  -->
        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

        <!-- Link to stylesheet and faviocn -->
        <link rel="stylesheet" href="css/welcome.css" media="screen" title="no title" charset="utf-8">
        <link rel="icon" href="./favicon.png">


        <!-- Link to jquery minified version-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>



        <!-- Link to angular and angular routes and ui router and angular Cookies and ng-file-upload-->
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.5/angular.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.5/angular-route.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.5/angular-cookies.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-ui-router/0.3.1/angular-ui-router.js"></script>
        <script src="js/ng-file-upload.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/camanjs/4.1.2/caman.full.js"></script>

        <!-- Link to bootstrap ui and bootstrap js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/1.3.3/ui-bootstrap-tpls.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

        <script src='js/jquery.js'></script>

        <script type="text/javascript" src='js/jquery.easing.1.3.js'></script>
        <!-- Jquery bs slider -->
        <script type="text/javascript" src='js/jquery.bxslider.js'></script>

        <link rel="stylesheet" href="css/jquery.bxslider.css" charset="utf-8">

        <!-- chart  -->
        <script src='js/angular-charts.min.js'>

        </script>
        <!-- Link to routes js file -->
        <script src='js/routes.js'></script>
        <!-- Links to factories -->
        <script src="js/factories.js"></script>
        <!-- Links to controllers -->
        <script src='js/controllers.js'></script>

        <!-- Charts -->
        <script src="http://cdn.zingchart.com/zingchart.min.js"></script>
        <script src="http://cdn.zingchart.com/angular/zingchart-angularjs.js"></script>

        <script type="text/javascript">
            console.log("Welcome to Twango !!");
            console.log("If you are interested in what goes on underneath the hood contact us on www.twango.com/carrers");
        </script>






    </head>
    <body>
        <div class="row">


            <div class="col-md-2 pull-left static">
                <div class="zoom_out">
                    <button class='glyphicon glyphicon-zoom-out'></button>
                </div>
                <ul>
                    <li><h6><a ui-sref="allUsers"><span class='glyphicon glyphicon-user'></span>&nbsp <span class='remove'>All users</span></a></h6></li>
                    <li><h6><a ui-sref="unapprovedUsers"><span class='glyphicon glyphicon-plus'></span>&nbsp<span class='remove'>Unapproved Users</span></a></h6></li>
                    <li class='options'><h6><a ui-sref="unapprovedUsers"><span class='glyphicon glyphicon-arrow-down'></span>&nbsp<span class='remove'>Rejected Users</span></a></h6></li>
                    <div class="fade">
                        <ul>
                            <li><p><a ui-sref="getWaitlistedUsers"><span class='glyphicon glyphicon-list-alt'></span>&nbsp<span class='remove'>Waitlisted Users</span></a></p></li>
                            <li><p><a ui-sref="unverifiedUsers"><span class='glyphicon glyphicon-asterisk'></span>&nbsp<span class='remove'>Not verified users</span></a></p></li>
                            <li><p><a ui-sref="blockedUsers"><span class='glyphicon glyphicon-ban-circle'></span>&nbsp<span class='remove'>Blocked Users</span></a></p></li>
                            <li><p><a ui-sref="reportedUsers"><span class='glyphicon glyphicon-star-empty'></span>&nbsp<span class='remove'>Reported Users</span></a></p></li>
                            <li><p><a ui-sref="rejectedUsers"><span class='glyphicon glyphicon-thumbs-down'></span>&nbsp<span class='remove'>Rejected Users</span></a></p></li>
                        </ul>
                    </div>
                    <li><h6><a ui-sref="graphs"><span class='glyphicon glyphicon-equalizer'></span>&nbsp<span class='remove'>Analysis</span></a></h6></li>
                    <li class='chats'><h6><a ui-sref="chats"><span class='glyphicon glyphicon-comment'></span>&nbsp<span class='remove'>Chats</span></a></h6></li>
                    <div class="chats-fade" style='color:white'>
                        <ul>
                            <li style='cursor: pointer;'><a ui-sref="chats({'type': '0'})">All message</a></li>
                            <li  style='cursor: pointer;'><a ui-sref="chats({'type': '1'})">First Chats</a></li>
                        </ul>
                    </div>
                    <li><h6><a ui-sref="activity"><span class='glyphicon glyphicon-list-alt'></span>&nbsp<span class='remove'>Activity</span></a></h6></li>
                    <li><h6><a ui-sref="notification"><span class='glyphicon glyphicon-envelope'></span>&nbsp<span class='remove'>Notification</span></a></h6></li>
                    <li><h6><a ui-sref="matchAssign"><span class='glyphicon glyphicon-indent-left'></span>&nbsp<span class='remove'>Match Assign</span></a></h6></li>
                    <li><h6><a ui-sref="stack"><span class='glyphicon glyphicon-align-justify'></span>&nbsp<span class='remove'>Stack</span></a></h6></li>
                </ul>
                <br>
                <div class="quote remove">

                </div>
            </div>
            <div class="col-md-10 pull-right change"  id='change'>
                <div ui-view >

                </div>

            </div>

        </div>
    </body>
</html>
