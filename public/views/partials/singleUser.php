

<div ng-controller='singleUserController' ng-init='getData()'>
        <div class="upper" style="background-image :url('{{singleUser.user_name.dpUrl}}'); background-size: cover; background-position: center center; background-color: rgba(0,0,0,.3); background-attachment: fixed">
            <div class="row" >
                <div class="col-md-4">

                </div>

                <div ng-include="'views/partials/ngInclude/centerpicture.php'" >

                </div>

            </div>

            <div class="col-md-4 pull-left">
                <button class='btn btn-info' ng-click="nextUser()"><span>NEXT</span></button>
            </div>
            <div class="col-md-4">

                <div class="info" style='text-align: center'>
                    <h3 class='name'><span><a href={{singleUser.user_name.profileUrl}} target='_blank'>{{singleUser.user_name.firstName + " " + singleUser.user_name.lastName}}</a></span></h3>
                    <h3 class='basic'><span>{{singleUser.user_name.age + ", " + singleUser.user_name.gender}}</span></h3>
                </div>
            </div>
            <div class="col-md-4">
                <a class='btn btn-warning'  ui-sref='stack({"id": id})' ng-click='getStackData()'><span>Stack</span></a>
            </div>

        </div>

        <div class="include" ng-include="'views/partials/ngInclude/profile.php'">
            <!-- Profile page goes here  -->
        </div>


    <div ng-if='!field'>
        <h1 class='title' class='toggle'> User Info <a href="#/profile/{{singleUser.user_name.id}}" ><span>{{singleUser.user_name.firstName + " " + singleUser.user_name.lastName}}</span></a></h1>
        <h3 ng-if="singleUser.liked_by.length == 0" class='title'>The user has no likes</h3>
        <table class='table table-hover' ng-if="singleUser.liked_by.length > 0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>STATUS</th>
                    <th>REASON</th>
                    <th>DATE</th>
                </tr>
            </thead>
            <tbody ng-controller='chatsController'>
                <tr ng-repeat="likes in singleUser.liked_by" ng-click='getChatInfo(singleUser.user_name.id, likes.userBId)'>
                    <td>{{likes.userBId}}</td>
                    <td>{{likes.firstName + " " + likes.lastName}}</td>
                    <td>{{(likes.isLike)}}</td>
                    <td>{{(likes.reason)  ? likes.reason : 'NOT SPECIFIED'}}</td>
                    <td>{{likes.date}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
