<!-- This file contains the Activity analysis -->
<div ng-controller='activityController' ng-init='getData()'>
    <div id='#loader' ng-show='loading' style='text-align: center; margin-top: 15%; height: 100vh'>
        <img src="/images/loader.gif" alt="Loading Data" height='400px' width='500px'/>
    </div>
    <div ng-show ='!loading'>
        <h2 style='text-align: center'>Activity Page</h2>

            <div class="set-date dropdown col-md-2" style="margin-right: 100px">
                  <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" style="width: 200px;">Select Date
                  <span class="caret"></span></button>
                  <ul class="dropdown-menu" style='text-align: center; width: 200px;'>
                    <li ng-click='filter.period = "today"; getFilteredData()'><span class='date'>Today</span></li>
                    <li ng-click='filter.period = "yesterday"; getFilteredData()'><span class='date'>Yesterday</span></li>
                    <li ng-click='filter.period = "lastWeek"; getFilteredData()'><span class='date'>Last 7 days</span></li>
                    <li ng-click='filter.period = "lastMonth"; getFilteredData()'><span class='date'>Last 30 days</span></li>
                  </ul>
            </div>

            <div class="custom-date">
                 <div class='dropdown col-md-2'>
                      <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" ng-model='filter.start'>Start Date
                      <span class="caret"></span></button>
                      <div class="dropdown-menu" style='text-align: center; margin-right: 100px' ng-click='dateFinder()'>
                          <uib-datepicker ng-model="filter.start" class="well well-sm" datepicker-options="options" ></uib-datepicker>
                      </div>
                  </div>
                  <div class="col-md-2" ng-show='filter || filter.period != "all"' >
                      <button type="button" class='btn btn-danger' ng-click='refreshData()'>Refresh</button>
                      <h6 ng-show='filter.period'>Period:&nbsp{{filter.period}}</h6>
                      <h6 ng-show='filter.gender'>Gender:&nbsp {{filter.gender}}</h6>
                      <h6 ng-show='filter.userA'>UserA:&nbsp {{filter.userA}}</h6>
                      <h6 ng-show='filter.userB'>UserB:&nbsp {{filter.userB}}</h6>
                      <h6 ng-show='filter.isLike.length'>isLike:&nbsp {{getIsLikes(filter.isLike)}}</h6>
                      <h6 ng-show='filter.isLikeB'>isLike B to A:&nbsp {{likeGenerator(filter.isLikeB)}}</h6>
                      <button type="button" ng-click='getFilteredData()' class='btn btn-info'>Submit</button>
                  </div>
                  <div class='dropdown col-md-2 pull-right'>
                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" ng-model='filter.end'>End Date
                        <span class="caret"></span></button>
                        <div class="dropdown-menu" style='text-align: center; ' ng-click='dateFinder()'>
                            <uib-datepicker ng-model="filter.end" class="well well-sm" datepicker-options="options" ></uib-datepicker>
                        </div>
                    </div>
            </div><br><br>
        <table class='table' ng-show='!message'>
            <thead>
                <tr>
                    <th>
                        <!-- Gender -->
                        <select ng-model='filter.gender'>
                            <option value='' selected disabled>Gender</option>
                            <option value='M'>M</option>
                            <option value='F'>F</option>
                        </select>
                    </th>
                    <th>
                        <!-- User A<br> -->
                        <input ng-model='filter.userA' type='text' style='width: 80px' placeholder='User A'>
                    </th>
                    <th>
                        <!-- User B<br> -->
                        <input ng-model='filter.userB' type='text' style='width: 80px' placeholder='User B'>
                    </th>
                    <th>
                        <!-- isLike -->
                        <select ng-model='filter.isLikeA'>
                            <option value='' diabled selected>IsLike</option>
                            <option value='0'>Seen</option>
                            <option value='1'>Dislike</option>
                            <option value='2'>Like</option>
                            <option value='3'>Connected</option>
                            <option value='4'>Sent Message</option>
                            <option value='5'>Unmatch</option>
                            <option value='6'>Got Unmatched</option>
                        </select>
                    </th>
                    <th>
                        isLike-B

                    </th>
                    <th ng-click='orderBy()'>
                        Copatibility
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat='data in users.data' ng-click='singleUserData(data)'>
                    <td>
                        {{data.profile.gender.charAt(0).toUpperCase()}}
                    </td>
                    <td>
                        {{data.userAId}}
                    </td>
                    <td>
                        {{data.userBId}}
                    </td>
                    <td>
                        {{likeGenerator(data.isLike)}}
                    </td>
                    <td>
                        {{(data.isLikeB_A[0].isLike) ? likeGenerator(data.isLikeB_A[0].isLike) : 'Not Seen'}}
                    </td>
                    <td>
                        {{data.compat[0].compatibilityAB}}
                    </td>
                </tr>
            </tbody>
        </table>
        <div style='text-align: center'>
            <button class='btn btn-info' ng-show='users.prev_page != null' ng-click='getData(filter, users.prev_page)'>Prev</button>&nbsp<button class='btn btn-info' ng-show='users.next_page != null' ng-click='getData(filter, users.next_page)'>Next</button>
        </div>
    </div>

    <div class='modal fade' id='person-chat'>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class='close' data-dismiss='modal'>&times;</button>
                    <h4 style='text-align:center'>Chat between {{chatInfo.userA[0].firstName + ' ' + chatInfo.userA[0].lastName}} and {{chatInfo.userB[0].firstName + ' ' + chatInfo.userB[0].lastName}}</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 pull-left user-chat-info">
                            <div id='picture' style="background-image: url('{{chatInfo.userA[0].originalDpUrl}}')" ng-click='chatPhotos()'>
                            </div>
                            <hr>
                            <div class="basic">

                                <h3 class='green'>Basic Info</h3>
                                <span >Name: <b>{{chatInfo.userA[0].firstName + ' ' + chatInfo.userA[0].lastName}} </b></span>
                                <span>Age: <b>{{chatInfo.userA[0].age}}</b></span>
                                <span>City: <b>{{chatInfo.userA[0].currentCity}}</b></span>
                                <span>Height: <b>{{chatInfo.userA[0].heightFeet}} Ft</b></span>
                            </div>
                            <div class="education">
                                <h3 class='green'>Education</h3>
                                <span >College: <b>{{(chatInfo.userA[0].college) ? chatInfo.userA[0].college : "Not Specified"}}</b></span><span>Degree: <b>{{chatInfo.userA[0].degree}}</b></span>
                            </div>
                            <div class="work">
                                <h3 class='green'>Work</h3>
                                <span>Company: <b>{{chatInfo.userA[0].companyName}}</b></span><br>
                                <span>Position: <b>{{chatInfo.userA[0].position}}</b></span>
                                <span>Salary: <b>{{chatInfo.userA[0].salary}}</b></span>
                            </div>
                        </div>
                        <div class="col-md-4" style="overflow: auto; max-height: 500px" >
                            <div ng-repeat='chat in chatInfo.message' ng-show='chatInfo.message.length > 0'>
                                    <div ng-if="chat.userAId == userA"  class='chat chat-a'>{{chat.message}}</div>
                                    <div ng-if="chat.userAId == userB"  class=' chat chat-b'>{{chat.message}}</div>
                            </div>
                            <div ng-show='!chatInfo.message.length'>
                                <div class="pull-left">
                                    <h3>{{(chatInfo.isLikeUserA) ? chatInfo.isLikeUserA : 'Not Seen'}}</h3>
                                </div>
                                <div class="pull-right">
                                    <h3>{{(chatInfo.isLikeUserB) ? chatInfo.isLikeUserB : 'Not Seen'}}</h3>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-4 pull-right user-chat-info" style='border: 1px solid black'>
                            <div id='picture' style="background-image: url('{{chatInfo.userB[0].originalDpUrl}}')"  ng-click='chatPhotos(userB, chatInfo.userB[0].gender)'>
                            </div>
                            <hr>
                            <div class="basic">
                                <h3 class='green'>Basic Info</h3>
                                <span>Age: <b>{{chatInfo.userB[0].age}}</b></span>
                                <span>City: <b>{{chatInfo.userB[0].currentCity}}</b></span>
                                <span>Height: <b>{{chatInfo.userB[0].heightFeet}} Ft</b></span>
                            </div>
                            <div class="education">
                                <h3 class='green'>Education</h3>
                                <span >College: <b>{{(chatInfo.userB[0].college) ? chatInfo.userB[0].college : "Not Specified"}}</b></span><span>Degree: <b>{{chatInfo.userB[0].degree}}</b></span>
                            </div>
                            <div class="work">
                                <h3 class='green'>Work</h3>
                                <span>Company: <b>{{chatInfo.userB[0].companyName}}</b></span><br>
                                <span>Position: <b>{{chatInfo.userB[0].position}}</b></span>
                                <span>Salary: <b>{{chatInfo.userB[0].salary}}</b></span>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer" style='text-align: center'>

                        <button class='btn btn-info' ng-click='chatPaginate(chatInfo.message.prev_page_url)' ng-if='chatInfo.message.prev_page_url'>Prev</button>&nbsp<button class='btn btn-info' ng-if='chatInfo.message.next_page_url' ng-click='chatPaginate(chatInfo.message.next_page_url)'>Next</button>

                </div>

            </div>
        </div>
    </div>
    <div ng-show='message' class='alert alert-danger' style='text-align: center; width: 300px; margin: 20% auto'>
        <h6>{{message}}</h6>
    </div>
    <script type="text/javascript">
        $('.dropdown-toggle').click(function(){
            $('.dropdown-menu').fadeToggle('slow');
        })

    </script>

</div>
