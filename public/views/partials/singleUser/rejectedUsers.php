<div ng-controller='singleUserController' ng-init='getData()'>
        <div class="upper" style="background-image :url('{{singleUser.user_name.dpUrl}}'); background-size: cover; background-position: center center; background-color: rgba(0,0,0,.3)">
            <div class="row">
                <div class="col-md-4">

                </div>

                <div ng-include="'views/partials/ngInclude/centerpicture.php'" >

                </div>

            </div>

            <div class="col-md-4 pull-left">
            </div>
            <div class="col-md-4">
                <div class="info" style='text-align: center'>
                    <h3 class='name'><span><a href={{singleUser.user_name.profileUrl}} target='_blank'>{{singleUser.user_name.firstName + " " + singleUser.user_name.lastName}}</a></span></h3>
                    <h3 class='basic'><span>{{singleUser.user_name.age + ", " + singleUser.user_name.gender}}</span></h3>
                </div>
            </div>
            <div class="col-md-4 pull-right">
            </div>
        </div>

        <div class="include" ng-include="'views/partials/ngInclude/profile.php'">
            <!-- Profile page goes here  -->
        </div>
</div>
