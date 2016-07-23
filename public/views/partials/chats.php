<div ng-controller='chatsController' ng-init='getData()'>
    <div ng-show='!false'>
        <h1 style="text-align: center">Recent chats</h1>
        <table class='table'>
            <thead>
                <th>
                    User AId
                </th>
                <th>
                    User BId
                </th>
                <th ng-show='show'>
                    Total message
                </th>
                <th>
                    Created At
                </th>
            </thead>
            <tbody>
                <tr ng-repeat='data in chatData["data"]' ng-click='getChatInfo(data.userAId, data.userBId)' data-toggle='modal' data-target='#person-chat'>
                    <td>
                        {{data.userAId}}
                    </td>
                    <td>
                        {{data.userBId}}
                    </td>
                    <td ng-show='show'>
                        {{data.message}}
                    </td>
                    <td>
                        {{data.date}}
                    </td>
                </tr>
            </tbody>
        </table>
        <div style='text-align: center; font-size: 20px'>
            <button class='btn btn-info' ng-if='chatData.prevPage != null' ng-click='getData(chatData.prevPage)'>Prev</button>&nbsp<button class='btn btn-info' ng-click='getData(chatData.nextPage)' ng-if='chatData.nextPage != null'>Next</button>
        </div>
        <!-- Chat model with user info --->
        <div class='modal fade' id='person-chat'>
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class='close' data-dismiss='modal'>&times;</button>
                        <div style="text-align: center">
                            Chat between {{chatInfo.userA[0].firstName + ' ' + chatInfo.userA[0].lastName}} and {{chatInfo.userB[0].firstName + ' ' + chatInfo.userB[0].lastName}}
                        </div>
                        </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4 pull-left user-chat-info">
                                <div id='picture' style="background-image: url('{{chatInfo.userA[0].originalDpUrl}}')" >
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
                                    <span>Company: <b>{{chatInfo.userA[0].companyName}}</b></span>
                                </div>
                            </div>
                            <div class="col-md-4" style="overflow: auto; max-height: 500px" >
                                <div ng-repeat='chat in chatInfo.message' >


                                        <div ng-show="chat.userAId == userA"  class='chat chat-a'><span>{{chat.message}}</span></div>
                                        <div ng-show="chat.userAId == userB"  class='chat chat-b'><span>{{chat.message}}</span></div>


                                </div>

                            </div>
                            <div class="col-md-4 pull-right user-chat-info" style='border: 1px solid black'>
                                <div id='picture' style="background-image: url('{{chatInfo.userB[0].originalDpUrl}}')" >
                                </div>
                                <hr>
                                <div class="basic">
                                    <h3 class='green'>Basic Info</h3>
                                    <span >Name: <b>{{chatInfo.userB[0].firstName + ' ' + chatInfo.userB[0].lastName}} </b></span>
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
                                    <span>Company: <b>{{chatInfo.userB[0].companyName}}</b></span>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer" style='text-align: center'>


                    </div>

                </div>
            </div>
        </div>
    </div>
    <div id='#loader' ng-show='loading'  style='text-align: center; margin-top: 15%; height: 100vh'>
        <img src="/images/loader.gif" alt="Loading Data" height='400px' width='500px'/>
    </div>


</div>
