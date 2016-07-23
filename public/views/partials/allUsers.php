
<div ng-controller ='getUsersController'>
    <div  class='container' style='padding-left: 0' ng-show='!loading'>
        <div class="title"><span>All Users</span></div>

        <div  class='col-md-8'>
            <div class="total total_users">
                 Users:<br>
                 {{ totalUsers.total}}
            </div>
            <div class="total total_guys">
                Guys:<br>
                {{totalUsers.male}}
            </div>
            <div class="total total_girls" style='display: inline-block'>
                Girls:</br>
                {{totalUsers.female}}
            </div>
        </div><br>
        <div class="col-md-3">
            <div class="order" ng-show='myOrderBy'>
                <button class='btn btn-warning'  ng-click='resetFilter()'>
                    Reset filter
                </button>
                <h4>Order:&nbsp{{myOrderBy}} in the format {{(reverse) ? 'asc' : 'desc'}}</h4>
            </div>
            <button class='btn btn-warning'  ng-show='searchParams.show' ng-click='resetFilter()'>
                Reset filter
            </button>

            <div ng-if='searchParams.show'>

                <div class="city" ng-show="searchParams.currentCity.length">
                    <h4>Cites Selected</h4>
                    <b ng-repeat='city in searchParams.currentCity'>{{city +', '}}</b>
                </div>
                <div class="status" ng-show="searchParams.status.length">
                    <h4>Status Selected</h4>
                    <b ng-repeat='status in searchParams.status'>{{status + ', '}}</b>
                </div>
                <div class="approved" ng-show="searchParams.isApproved.length">
                    <h4>Approved</h4>
                    <b ng-repeat='approved in searchParams.isApproved'>{{approved+ ', '}}</b>
                </div>
                <div class="gender">
                    <h4>Gender</h4>
                    <b>{{searchParams.gender[0]}}</b>
                </div>
                <button class='btn btn-info' ng-click='getSearchedData.getData(searchParams)'>Submit</button>
            </div>
        </div>




        <table class='table table-hover' ng-if='message == undefined'>
            <thead align='center'>
                <tr>
                    <th><span  ng-click='orderByMe("id")'>ID</span><br>
                        <input ng-model='short.user.id' style='width:30px;' ng-keydown='checkEnter($event, {"id": short.user.id})'>

                    </th>
                    <th>Name<br>
                        <input  uib-typeahead='name for name in getNames($viewValue)' typeahead-show-hint="true" typeahead-min-length="3" placeholder='Enter Name' ng-model='short.user.firstName' ng-keydown='checkEnter($event, {"firstName": short.user.firstName} )'/>

                    </th>
                    <th ng-click="options('gender')">Gender
                        <select class="gender" ng-model="short.gender" ng-options='gender for gender in object.gender' ng-change='getSearchedData.createObject("gender", short.gender)'>
                            <option selected disabled value>G</option>
                        </select>
                    </th>
                    <th ><span ng-click='orderByMe("age")'>Age</span>
                        <!-- <div class="age">
                            <input  ng-model="short.age", style="width:60px">
                            <span class='glyphicon glyphicon-search' ng-click='getSearchedData.createObject("age", short.age)'></span>
                        </div> -->
                    </th>
                    <th  style='width: 30px'><span ng-click="orderByMe('iAm')" class='reduce-width'>orientation</span><br>
                        <!-- <select ng-model='short.iAm' ng-options='iAm for iAm in '>
                            <option value='' selected disabled>Value</option>
                        </select> -->
                    </th>
                    <th ng-click="getCities()">City
                        <div>
                            <select class="city" ng-model="short.currentCity" ng-options='city for city in object.currentCity' ng-change='getSearchedData.createObject("currentCity", short.currentCity)'>
                                <option selected disabled value>City</option>
                                <!-- <option ng-repeat="city in cities" value={{city}} ng-mouseup='filteredUsers()'>{{city}}</option> -->
                            </select>

                        </div>
                    </th>
                    <th style='width: 30px'><span ng-click="orderByMe('currencyPurchased')" >Purchased</span><br>
                        <!-- <select ng-model='short.currencyPurchased'>
                            <option value='' selected disabled>Value</option>
                        </select> -->
                        <!-- <input type="text" name="purchased" style='width:20px; display:inline-block' ng-model="short.currencyPurchased" /><span ng-click='getSearchedData.createObject("currencyPurchased", short.currencyPurchased)' class='glyphicon glyphicon-search'></span> -->
                    </th>
                    <th  style='width: 30px'><span ng-click="orderByMe('currencyUsed')" class='reduce-width'>Spent</span><br>
                        <!-- <select ng-model='short.currencyUsed'>
                            <option value='' selected disabled>Value</option>
                        </select> -->
                        <!-- <input type="text" name="spent" style='width:40px; display:inline-block' ng-model="short.currencyUsed"/><span ng-click='getSearchedData.createObject("currencyUsed", short.currencyUsed)' class='glyphicon glyphicon-search'></span> -->
                    </th>
                    <th ng-click="orderByMe('lastActiveDate')" ng-class="{reverse: reverse}">Last Active</th>
                    <th ng-click="orderByMe('lastActiveDate')" ng-class="{reverse: reverse}">Registered</th>
                    <th ng-click="orderByMe('like')" ng-class="{reverse:reverse}">ML</th>
                    <th ng-click="orderByMe('daysToLastML')" ng-class={reverse:reverse}>
                        Last ML
                    </th>
                    <th ng-click="options('status')">
                        Status
                        <select ng-model='short.user.status' ng-options='state for state in object.status' ng-change='getSearchedData.createObject("status", short.user.status)'>
                            <option value selected disabled>S</option>
                        </select>
                    </th>
                    <th ng-click='options("isApproved")'>
                        Approved
                        <select  ng-model='short.user.isApproved' ng-options='approved for approved in object.isApproved' ng-change='getSearchedData.createObject("isApproved", short.user.isApproved)'>
                            <option value selected disabled>A</option>
                        </select>
                    </th>
                </tr>
            </thead>
            <!-- {{filteredResult}} -->

            <tbody >
                <!-- Angular filter only works on text boxes and not on numbers -->
                <!-- <tr ng-repeat='user in users.info.data | orderBy: ((searchParams.show) ? (myOrderBy) : undefined) : ((searchParams.show) ? reverse : null)' ng-click='singleUser("/singleUser/" + user.user.id + "/" + users.info.data.indexOf(user))' style='cursor: pointer'> -->
                <tr ng-repeat='user in users.info.data' ng-click='singleUser("/singleUser/" + user.user.id + "/" + users.info.data.indexOf(user))' style='cursor: pointer'>

                    <td>{{user.user.id}}</td>
                    <td>{{user.user.firstName + " " + user.user.lastName | uppercase}}</td>
                    <td>{{user.gender}}</td>
                    <td>{{user.age}}</td>
                    <td>{{user.iAm}}</td>
                    <td>{{user.currentCity}}</td>
                    <td>{{user.currencyPurchased}}</td>
                    <td>{{user.currencyUsed}}</td>
                    <td>{{user.lastActiveDate.split(" ")[0]}}</td>
                    <td>{{user.registered}} <span ng-if="$user.registered != 'not active'">days ago</span></td>
                    <td>{{user.like}}</td>
                    <td>{{(user.daysToLastML != null) ? user.daysToLastML + " days ago" : "None"}} </td>
                    <td>{{user.user.status}}</td>
                    <td>
                        {{user.user.isApproved}}
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="pageUrl">
            <button ng-click="page('prev')" ng-show="users.info.prev_page_url != null" class='btn btn-info' style='margin: 10px;'>Prev</button></span> <button ng-click="page('next')" class='btn btn-info' ng-show="users.info.next_page_url != null">Next</button><br>

        </div>

    </div>
    <div class="alert alert-danger" ng-if='message!=undefined' style='text-align:center'>
        <h1>No data has been found</h1>
    </div>
    <div id='#loader' ng-show='loading' style='text-align: center; margin-top: 15%; height: 100vh'>
        <img src="/images/loader.gif" alt="Loading Data" height='400px' width='500px'/>
    </div>
</div>
