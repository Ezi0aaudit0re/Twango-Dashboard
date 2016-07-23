<!-- This page is included in every single users info page
    It also includes all the modals that pop up
-->
    <div class="lower">
        <div class="col-md-9 pull-left">
            <div class="basic">
                <h3 class='green'>Basic Info</h3>
                <span>Height: <b>{{singleUser.user_name.heightFeet}} ft</b></span><span>Religion: <b>{{singleUser.user_name.religion}}</b></span><span>Current City: <b>{{singleUser.user_name.currentCity}}</b></span>
            </div>
            <div class="work">
                <h3 class='green'>Work</h3>
                <span>Organisation: <b>{{singleUser.user_name.companyName}}</b></span><span>Position: <b>{{singleUser.user_name.position}}</b></span><span>Salary: <b>{{singleUser.user_name.salary}}</b></span>
            </div>
            <div class="education">
                <h3 class='green'>Education</h3>
                <span>College: <b>{{(singleUser.user_name.college) ? singleUser.user_name.college : "Not Specified"}}</b></span><span>Degree: <b>{{singleUser.user_name.degree}}</b></span>
            </div>
            <div class="interests">
                <h3 class='green'>Interests</h3>
                <b>{{singleUser.user_name.interests}}</b>
            </div>
        </div><!-- Close info tag -->
        <div class="col-md-3 college" style='margin: auto' ><!-- College Tage starts here -->
            <div ng-hide='button == "addCollege"'>
                <input  type="text" ng-model="college"  uib-typeahead="college as college.collegeName for college in getColleges($viewValue)"  typeahead-show-hint="true" typeahead-min-length="3" class="form-control" placeholder='College Score' />
            </div>
            <input ng-show='button == "addCollege"'  ng-value='singleUser.user_name.college' />
            <input ng-show='button =="addCollege"' ng-model='college.collegeScore' placeholder='Score' />
            <button type="button" class="btn btn-info" style="margin-top: 20px" ng-click="updateCollegeScore(college)" >
                Update
            </button>
            <button type='button' class='btn btn-info' style="margin-top: 20px" ng-click="updateCollegeScore(college, 'addCollege')">Add College</button>
            <div class="alert alert-success" ng-if='message' style='margin-top: 10%'>
                {{message}}
            </div>
            <h1 style= "color:white" ng-if="message == undefined">College Score: {{singleUser.user_name.collegeScore}}</h1>
        </div><!-- Close college tag -->
    </div>


    <!-- photos modal -->
    <div class="modal fade" id='modal1'>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button ng-click='message=undefined' type="button" class='close' data-dismiss='modal'>&times;</button>
                    <h3 class='modal-title'>Photos</h3>
                </div>
                <div class="modal-body" >
                    <div class='row' style="display: inline-block; height: 45ev" ng-show='file == "guy_images"'>
                        <img src="images/guy_images/2[a].jpg" alt="" height='200px' width='180px'/>
                        <img src="images/guy_images/2[b].jpg" alt="" height='200px' width='180px'/>
                        <img src="images/guy_images/3[a].jpg" alt="" height='200px' width='180px'/>
                        <img src="images/guy_images/4[a].jpg" alt="" height='200px' width='180px'/>
                        <img src="images/guy_images/4[b].jpg" alt="" height='200px' width='180px'/>
                        <img src="images/guy_images/5[a].jpg" alt="" height='200px' width='175px'/>
                        <img src="images/guy_images/5[b].jpg" alt="" height='200px' width='175px'/><br>
                        <div class="col-md-4 pull-left" style="margin-right: -10px">
                            <img src="images/guy_images/6[a].jpg" alt="" height='170px' width='170px'/>
                            <img src="images/guy_images/6[b].jpg" alt="" height='170px' width='170px'/>
                            <img src="images/guy_images/9[a].jpg" alt="" height='200px' width='170px'/>
                            <img src="images/guy_images/9[b].jpg" alt="" height='200px' width='170px'/>
                        </div>
                        <div class="col-md-4" style='height: 600px; width: 450px; margin-left: -3px;'>
                            <div class="center-images" style="border: 2px solid black; padding:5px; text-align: center">
                                <div ng-show='show != false'>
                                    <select ng-model="rating" ng-options='value for value in values' >
                                        <option value selected disabled>Photo Rating</option>
                                    </select>
                                    <button type="button" class='btn btn-success' ng-click='addPhotoScore(rating)' ng-show='button != "approve"'>Submit</button>
                                    <button type="button" class='btn btn-success' ng-click='unverifyUser(rating)' ng-show='button == "approve"'>Approve</button>

                                </div>
                                <div  style='display: inline-block' >
                                    <ul  class='bxSlider'  >
                                        <li ng-repeat='photo in photos' bx-slider>
                                            <img ng-src={{photo.imgUrl}} alt="Image Not found"  />
                                            <button type="button" class='btn btn-info' ng-click='makeProfilePicture(photo.imgUrl)'>Make Profile Picture</button>
                                            <button type="button" class='btn btn-danger' ng-click='deletePhoto(photo.imgUrl)'>Delete Picture</button>
                                            <button type="button" ng-click='editImage(photo.imgUrl)' class='btn btn-success'>Edit</button>
                                            <button type="button" ng-click=saveFilteredImage() class='btn btn-success' ng-show='imageUrl == photo.imgUrl'>Save</button>
                                        </li>
                                    </ul>
                                </div>
                                <div class='alert alert-success' ng-show='message'>
                                    {{message}}
                                </div>
                                <div id='#loader' ng-show='loading && imageUrl' style='text-align: center'>
                                    Note - Only works if you have a reliable internet conenction
                                    <img src="/images/loader.gif" alt="Loading Data" height='200px' width='200px'/>
                                </div>
                                <div class="toggle-show" ng-show='!loading'>

                                    <canvas id="canvasM" width="300" height="300" />
                                    </canvas><br>
                                    <button type="button" name="button" ng-click='filter("vintage")'  class='btn btn-info vintage'>Vintage</button>
                                    <button type="button" name="button" ng-click='filter("greyscale")' class='btn btn-info greyscale'>greyscale</button>
                                    <button type="button" name="button" ng-click='filter("lomo")' class='btn btn-info lomo'>lomo</button>
                                    <button type="button" name="button" ng-click='filter("clarity")' class='btn btn-info clarity'>Clarity</button>
                                    <button type="button" name="button" ng-click='filter("sunrise")' class='btn btn-info sunrise'>sunrise</button>
                                    <button type="button" name="button" ng-click='filter("love")' class='btn btn-info love'>Love</button>
                                    <button type="button" name="button" ng-click='filter("pinhole")' class='btn btn-info pinhole'>PinHole</button>
                                    <button type="button" name="button" ng-click='filter("jarques")' class='btn btn-info jarques'>Jarques</button>
                                    <button type="button" name="button" ng-click='filter("hazyDays")' class='btn btn-info hazyDays'>Hazy Days</button>
                                    <button type="button" name="button" ng-click='filter("herMajesty")' class='btn btn-info herMajesty'>Her Majesty</button>
                                    <button type="button" name="button" ng-click='filter("nostalgia")' class='btn btn-info nostalgia'>Nostalgia</button>
                                    <button type="button" name="button" ng-click='filter("hemingway")' class='btn btn-info hemingway'>Hemingway</button>
                                    <button type="button" name="button" ng-click='filter("grungy")' class='btn btn-info grungy'>Grungy</button>
                                    <button type="button" name="button" ng-click='filter("glowingSun")' class='btn btn-info glowingSun'>Glowing Sun</button>
                                    <button type="button" name="button" ng-click='filter("oldBoot")' class='btn btn-info oldBoot'>Old Boot</button>
                                    <button type="button" name="button" ng-click='filter("refresh")' class='btn btn-warning refresh'>Refresh</button>
                                </div>

                            </div>

                        </div>
                        <img src="images/guy_images/7[a].jpg" alt="" height='200px' width='200px'/>
                        <img src="images/guy_images/7[b].jpg" alt="" height='200px' width='200px'/>
                        <img src="images/guy_images/8[a].jpg" alt="" height='200px' width='200px'/>
                        <img src="images/guy_images/8[b].jpg" alt="" height='200px' width='200px'/><br>


                    </div>
                    <div class='row' style="display: inline-block; height: 45ev" ng-show='file == "girls_images"'>
                        <img src="images/girls_images/2[a].jpg" alt="" height='200px' width='180px'/>
                        <img src="images/girls_images/3[a].jpg" alt="" height='200px' width='180px'/>
                        <img src="images/girls_images/3[b].jpg" alt="" height='200px' width='180px'/>
                        <img src="images/girls_images/4[a].jpg" alt="" height='200px' width='180px'/>
                        <img src="images/girls_images/4[b].jpg" alt="" height='200px' width='180px'/>
                        <img src="images/girls_images/5[a].jpg" alt="" height='200px' width='175px'/>
                        <img src="images/girls_images/5[b].jpg" alt="" height='200px' width='175px'/><br>
                        <div class="col-md-4 pull-left" style="margin-right: -10px">
                            <img src="images/girls_images/6[a].jpg" alt="" height='170px' width='170px'/>
                            <img src="images/girls_images/6[b].jpg" alt="" height='170px' width='170px'/>
                            <img src="images/girls_images/9[a].jpg" alt="" height='200px' width='170px'/>
                            <img src="images/girls_images/9[b].jpg" alt="" height='200px' width='170px'/>
                        </div>
                        <div class="col-md-4" style='height: 600px; width: 450px; margin-left: -3px;'>
                            <div class="center-images" style="border: 2px solid black; padding:5px; text-align: center">
                                <div ng-show='show != false'>
                                    <select ng-model="rating" ng-options='value for value in values' >
                                        <option value selected disabled>Photo Rating</option>
                                    </select>
                                    <button type="button" class='btn btn-success' ng-click='addPhotoScore(rating)' ng-show='button != "approve"'>Submit</button>
                                    <button type="button" class='btn btn-success' ng-click='unverifyUser(rating)' ng-show='button == "approve"'>Approve</button>                                </div>
                                <div  style='display: inline-block' >
                                    <ul  class='bxSlider'  >
                                        <li ng-repeat='photo in photos'>
                                            <img ng-src={{photo.imgUrl}} alt="Image Not found" style="max-height: 400px; max-width: 400px" />
                                            <button type="button" class='btn btn-info' ng-click='makeProfilePicture(photo.imgUrl)'>Make Profile Picture</button>
                                            <button type="button" class='btn btn-danger' ng-click='deletePhoto(photo.imgUrl)'>Delete Picture</button>
                                            <button type="button" ng-click='editImage(photo.imgUrl)' class='btn btn-success'>Edit</button>
                                            <button type="button" ng-click=saveFilteredImage() class='btn btn-success' ng-show='imageUrl == photo.imgUrl'>Save</button>
                                        </li>
                                    </ul>
                                </div>
                                <div class='alert alert-success' ng-show='message'>
                                    {{message}}
                                </div>
                                <div id='#loader' ng-show='loading && imageUrl' style='text-align: center'>
                                    Note - Only works if you have a reliable internet conenction
                                    <img src="/images/loader.gif" alt="Loading Data" height='200px' width='200px'/>
                                </div>
                                <div class="toggle-show" ng-show='!loading'>
                                    <canvas id="canvasF" width="300" height="300" >
                                    </canvas><br>
                                    <button type="button" name="button" ng-click='filter("vintage")'  class='btn btn-info vintage'>Vintage</button>
                                    <button type="button" name="button" ng-click='filter("greyscale")' class='btn btn-info greyscale'>greyscale</button>
                                    <button type="button" name="button" ng-click='filter("lomo")' class='btn btn-info lomo'>lomo</button>
                                    <button type="button" name="button" ng-click='filter("clarity")' class='btn btn-info clarity'>Clarity</button>
                                    <button type="button" name="button" ng-click='filter("sunrise")' class='btn btn-info sunrise'>sunrise</button>
                                    <button type="button" name="button" ng-click='filter("love")' class='btn btn-info love'>Love</button>
                                    <button type="button" name="button" ng-click='filter("pinhole")' class='btn btn-info pinhole'>PinHole</button>
                                    <button type="button" name="button" ng-click='filter("jarques")' class='btn btn-info jarques'>Jarques</button>
                                    <button type="button" name="button" ng-click='filter("refresh")' class='btn btn-warning refresh'>Refresh</button>
                                </div>
                            </div>
                        </div>
                        <img src="images/girls_images/7[a].jpg" alt="" height='200px' width='200px'/>
                        <img src="images/girls_images/7[b].jpg" alt="" height='200px' width='200px'/>
                        <img src="images/girls_images/8[a].jpg" alt="" height='200px' width='200px'/>
                        <img src="images/girls_images/8[b].jpg" alt="" height='200px' width='200px'/><br>


                    </div>

                </div>
                <div class="modal-footer">
                </div>
            </div>
            <script type="text/javascript">
            </script>
        </div>

    </div><!-- Close photo modal -->

 <!-- Multiple button User Modal -->
    <div class="modal" id='modal-unverify'>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class='close' data-dismiss='modal'>&times;</button>
                    <h3 class='modal-title'>{{button}} user</h3>
                </div>
                <div class="modal-body">
                    <form>
                        <select ng-model='unverifydata.reason'>
                            <option disabled selected value>Select One </option>
                            <option value="photo not present">Photo Not Present </option>
                            <option value="reason">Other </option>
                            <option value="Not in city">Not in the city</option>
                        </select><br>
                        <input type="text" ng-model='unverifydata.mainReason' ng-if='unverifydata.reason == "reason"' placeholder='{{unverifydata.reason}}'>
                    </form>
                    <div class="status" ng-show='button == "dissaprove"'  style='padding: 5px;'>
                        Status
                        <select ng-model='unverifydata.status'>
                            <option value='2'>2</option>
                            <option value='3'>3</option>
                            <option value='4'>4</option>
                        </select>
                    </div><br>
                    <button type="button" class='btn btn-success' ng-click="unverifyUser()" style='margin-top: 10px;' ng-show='unverifydata.mainReason || unverifydata.reason'>Submit</button>

                </div>
            </div>

        </div>
    </div><!-- Close unverify modal -->

    <!-- remove user modal -->
    <div class="modal fade" id='modal-remove'>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class='close' data-dismiss='modal'>&times;</button>
                    <h3 class='modal-title'>Remove User</h3>
                </div>
                <div class="modal-body">
                    <button class='btn btn-danger' ng-click='removeUser()'>Remove</button>
                </div>

            </div>

        </div>
    </div>

    <!-- Disapprov user modal -->
    <div class="modal" id='modal-dissaprove'>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class='close' data-dismiss='modal'>&times;</button>
                    <h3 class='modal-title'>Disaaprove User</h3>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer" style='text-align: center'>
                    <button ng-if='button == "dissaprove"' class='btn btn-danger' ng-click='unverifyUser()'>Dissaprove</button>
                    <button ng-if='button == "waitlist"' class='btn btn-danger'>Waitlist User</button>
                </div>
            </div>
        </div>
    </div>



    <!-- Chat model with user info -->
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

                            <div ng-show='chatInfo.message.length == 0' >
                                <div class="pull-left">
                                    <h3>{{status.userA.isLike}}</h3>
                                </div>
                                <div class="pull-right">
                                    <h3>{{status.userB.isLike }}</h3>
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
