<div ng-controller='fieldController'>
    <div  class='container' ng-show='!loading'>
        <div class="title"><span>Users Information</span></div>
        <div class="total total_users">
             Users:<br>
             {{ totalUsers.total}}
        </div>
        <div class="total total_guys">
            Guys:<br>
            {{totalUsers.male}}
        </div>
        <div class="total total_girls">
            Girls:</br>
            {{totalUsers.female}}
        </div>
        <table class='table table-hover'>
            <thead align='center'>
                <tr>
                    <th>ID<br>
                        <input ng-model='short.id' style="width:60px" ng-keyup='filteredUsers()'>
                    </th>
                    <th>Name<br>
                        <input type="text" name="search" ng-model="short.firstName" placeholder="Enter Name" ng-keyup='filteredUsers()'>
                    </th>
                    <th ng-click="orderByMe('gender')" ng-class='{reverse:reverse}'>Gender
                    </th>
                    <th>Age
                        <div class="age">
                            <input  ng-model="short.profile.age", style="width:60px" ng-keyup='filteredUsers()'><span class='glyphicon glyphicon-search' ng-click='sortBy("age")'></span>
                        </div>
                    </th>
                    <th ng-click="orderByMe('currentCity')">City
                    </th>
                </tr>
            </thead>
            <tbody>

                <tr ng-repeat='user in filteredResult = (userInfo |filter: short | orderBy: myOrderBy:reverse)' ng-click="goto('singleUser/' + user.id + '/' + userInfo.indexOf(user) + '/' + route)">
                    <td>{{user.id}}</td>
                    <td>{{user.firstName + " " + user.lastName | uppercase}}</td>
                    <td>{{user.profile.gender}}</td>
                    <td>{{user.profile.age}}</td>
                    <td>{{user.profile.currentCity}}</td>
                </tr>

            </tbody>
        </table>
        <!-- <div class="pageUrl">
            <button ng-click="page('prev')" ng-if="users.prevPage != null">Prev</button> <button ng-click="page('next')">Next</button>
        </div> -->
    </div>
    <div id='#loader' ng-show='loading'  style='text-align: center; margin-top: 15%; height: 100vh'>
        <img src="/images/loader.gif" alt="Loading Data" height='400px' width='500px'/>
    </div>

</div>
