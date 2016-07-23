<div ng-controller='singleUserController' ng-init='getData()'>
        <div class="upper" style="background-image :url('{{singleUser.user_name.dpUrl}}'); background-size: cover; background-position: center center; background-color: rgba(0,0,0,.3)">
            <div class="row">
                <div class="col-md-4">

                </div>

                <div ng-include="'views/partials/ngInclude/centerpicture.php'" >

                </div>

            </div>

            <div class="col-md-4 pull-left">
                <button class='btn btn-info' ng-click='nextUser()'>Next</button>
                <button class='btn btn-danger' data-toggle='modal' data-target='#modal-unverify' ng-click='button="dissaprove"; unverifydata.status = 2'>Dissaprove</button>
                <button class='btn btn-danger' ng-click='button="waitlist"; unverifyUser();' >WAITLIST</button>

            </div>
            <div class="col-md-4">
                <div class="info" style='text-align: center'>
                    <h3 class='name'><span><a href={{singleUser.user_name.profileUrl}} target='_blank'>{{singleUser.user_name.firstName + " " + singleUser.user_name.lastName}}</a></span></h3>
                    <h3 class='basic'><span>{{singleUser.user_name.age + ", " + singleUser.user_name.gender}}</span></h3>
                </div>
            </div>
            <div class="col-md-4 pull-right">
                <button class='btn btn-info' data-toggle='modal' data-target='#modal1' ng-click='button="approve"; getPhotos();'>Approve</button>
                <button class='btn btn-danger' ng-click='button="block"' data-toggle='modal' data-target='#modal-unverify'>Block</button>
            </div>
        </div>

        <div class="include" ng-include="'views/partials/ngInclude/profile.php'">
            <!-- Profile page goes here  -->
        </div>
</div>
