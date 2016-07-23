var myModule = angular.module('myApp');

var users = {}; // this variable will store all teh information stored in $scope.users

var totalUsers =  function(usersArray){
    var total = 0;
    var male = 0;
    var female = 0;
    var unknown = 0;
    usersArray.map(function(data){
        total++;
        if(data.profile != null){
            if(data.profile.gender){
                if(data.profile.gender.charAt(0).toUpperCase() == 'M' ) {male++;}
                else if(data.profile.gender.charAt(0).toUpperCase() == 'F') {female++;}
                else {unknown++;}
            }
            else {
                unknown++;
            }
            /* This will work for the filtered result page */

        }
        else if(data.gender){
            /* This will work for all users result page */
            if(data.gender){
                if(data.gender.charAt(0).toUpperCase() == 'M' ) {male++;}
                else if(data.gender.charAt(0).toUpperCase() == 'F') {female++;}
                else {unknown++;}
            }
            else {
                unknown++;
            }

        }

    })
    return {'male': male, 'female': female, 'total': total};



}

var likeGenerator = function(like){
    if(like == 1){
        return 'Dislike';
    }
    else if(like == 2){
        return 'Like';
    }
    else if(like == 3){
        return 'Connected';
    }
    else if(like == 4){
        return 'Sent Message';
    }
    else if(like == 5){
        return 'UnMatch';
    }
    else if(like == 6){
        return 'Got Unmatched';
    }
    else if(like == 0){
        return 'Seen';
    }
}
/* We use setTimeout at some places where filter is applied later to update the user array */

/* ------------------- Controllers that deal with All usersInfo -------------------------------*/
myModule.controller('getUsersController', ['$scope', '$location', '$state', '$cookies', '$filter', 'getUsersFactory', 'singleUserFactory', function($scope, $location, $state, $cookies, $filter, getUsersFactory, singleUserFactory){
    $scope.object= {};
    $scope.currentCity = [];
    $scope.loading = true;
    $scope.filteredResult = [];
    $scope.users = {'info': {'data': []}};
    $scope.myOrderBy = undefined;
    $scope.searchParams = $cookies.getObject('searchParams');
    if($scope.searchParams == undefined){
        $scope.searchParams = {
            'show': false
        }
        $cookies.putObject('searchParams', $scope.searchParams);
    }
    $scope.message = undefined;

    /*this code snippet allows a title of a colum to be clicked to sort the data
    * true = ascending, false = descending
    */
    $scope.reverse = true;
    $scope.orderByMe = function(x, reverse=$scope.reverse){
        console.log(x);
        if($scope.searchParams.show == true && users.info && $scope.users.info){
            $scope.reverse = ($.isEmptyObject($cookies.getObject('order'))) ? true : $cookies.getObject('order').reverse;
            $scope.reverse = ($scope.myOrderBy === x) ? !$scope.reverse : (($.isEmptyObject($cookies.getObject('order'))) ? false : !$cookies.getObject('order').reverse);
            $scope.myOrderBy = x;
            $scope.users.info.data = users.info.data = $filter('orderBy')($scope.users.info.data, {'orderBy': $scope.orderByMe}, {'reverse': $scope.reverse});
            $cookies.putObject('order', {'orderBy': x, 'reverse': $scope.reverse})
            $scope.loading = false;
            return;
        }

        if($scope.myOrderBy == undefined && !$.isEmptyObject($cookies.getObject('order'))){
            $scope.reverse = $cookies.getObject('order').reverse;
        }
        else {
            $scope.reverse = reverse =  ($scope.myOrderBy === x) ? !$scope.reverse : false;
        }
        // start the loader again
        $scope.loading = true;
        if($scope.reverse == false){
            getUsersFactory.sortDataBy(x, 'desc', function(data){
                if(data.code == 200){
                    data.response.info.data = $scope.genderCreator(data.response.info.data);
                    $scope.totalUsers = totalUsers(data.response.info.data);

                    $scope.users = users = data.response;

                }
            })
        }
        else {
            getUsersFactory.sortDataBy(x, 'asc', function(data){
                if(data.code == 200){
                    data.response.info.data = $scope.genderCreator(data.response.info.data);
                    $scope.totalUsers = totalUsers(data.response.info.data);
                    $scope.users = users = data.response;
                }

            })
        }
        // $scope.totalUsers = totalUsers(users.info.data);
        $scope.myOrderBy = x;
        $cookies.putObject('order', {'orderBy': x, 'reverse': reverse});
        $('.order').fadeIn('slow');
        $scope.loading = false;


    }

    $scope.genderCreator = function(array){
        array.map(function(value){
            /* Change the gender to M or F */
            value.gender = value.gender.charAt(0).toUpperCase();
        })
        return array;
    }

    $scope.getSearchedData = {
            'createObject': function(area, value){
                /* This function takes in 2 parameters
                * we create an object with this
                */
                $scope.searchParams.show = true;
                if($scope.searchParams[area] == undefined)
                {/* If an object.array is not set we set it */
                    $scope.searchParams[area] = [];
                }
                $scope.searchParams[area].push(value);
                $cookies.putObject('searchParams', $scope.searchParams);
                $cookies.putObject('order', {}); // empty the previous order cookies
                $scope.reverse = true;
                $scope.myOrderBy = undefined;
                if($scope.searchParams.firstName || $scope.searchParams.currencyPurchased || $scope.searchParams.id || $scope.searchParams.currencyUsed || $scope.searchParams.age){ $scope.getSearchedData.getData($scope.searchParams)}

            },

            'getData': function(object)
            {
                getUsersFactory.getSearchedData(object, function(data){
                    if(data.code == 200 ){
                        if(!data.response.info.data)
                        {
                            data.response.info.data = data.response.info
                        }
                        var arr = [];

                        for(item in data.response.info.data){
                            arr.push(data.response.info.data[item])
                        }
                        data.response.info.data = arr;
                        if(data.response.info.data.length < 1) { $scope.message = "no data has been found"; return;}


                        if(object.firstName || object.id){
                            /* Take directly to that user */
                            $state.go('singleUser', {'id':data.response.info.data[0].userId , 'index': 0})
                            return;

                        }
                        else {
                            $scope.users = users = data.response;

                        }

                        if(!$.isEmptyObject($cookies.getObject('order')) && data.response.info.data.length && $cookies.getObject('order').reverse == false){
                            // if cookie is set for order by then set the value of reverse and myOrderBy as stored in cookies
                            $scope.users.info.data = users.info.data = $filter('orderBy')($scope.users.info.data, {'orderBy': $cookies.getObject('order').orderBy}, {'reverse': $cookies.getObject('order').reverse});
                        }


                        $scope.users.info.data.map(function(value){
                            /* Change the gender to M or F */
                            value.gender = value.gender.charAt(0).toUpperCase();
                        })
                        if(users.info){
                            $scope.totalUsers = totalUsers(users.info.data);
                        }
                        $scope.loading = false;

                    }
                })
            }
    }



            if($scope.searchParams.show == true){
                $scope.getSearchedData.getData($cookies.getObject('searchParams'));
            }
            else {
                getUsersFactory.getUserInfo(function(data){
                    if(!$.isEmptyObject($cookies.getObject('order'))){
                        $scope.orderByMe($cookies.getObject('order').orderBy, $cookies.getObject('order').reverse);
                        return;
                    }
                /* This function gets data from the getUserFactory */
                    if(data.code == 200){
                        $scope.users = users =  data.response;

                        $scope.users.info.data.map(function(value){
                            /* Change the gender to M or F */
                            value.gender = value.gender.charAt(0).toUpperCase();
                        })

                        $scope.totalUsers = {'male': users.male, 'female': users.female, 'total': users.total}
                        $scope.loading = false;
                    }
                })
            }




    /* ----------------------------*/

    $scope.singleUser = function(path){
    $scope.index = path.split('/').pop();
        $state.go('singleUser', {'id': path.split('/')[2], 'index': $scope.index});
    }

    $scope.resetFilter = function(){
        /* This function resets the filter results */
        $cookies.putObject('order', {}); //empty the cookies
        $cookies.putObject('searchParams', {'show': false});
        $state.go($state.current, {}, {reload: true})
    }


    $scope.options = function(param){
        /* This function is called to get values specific to select from the data
        * Return alls the options if the user has not started filtering his result
        */
        $scope.object = {
            'status': [0,1,2,3,4,5,6,7,8],
            'isApproved': [0,1,2,3,4,5],
            'gender': ['M', 'F'],
            'currentCity': $scope.object.currentCity
        }



    }

    $scope.checkEnter = function($event, object){

        if($event.keyCode == 13)
        {
            $scope.getSearchedData.getData(object);
        }
    }


    $scope.getCities = function(){
        if($scope.object['currentCity'] == undefined){
            getUsersFactory.getCities(function(data){
                if(data.code == 200){
                    $scope.object['currentCity'] = data.response.map(function(data){
                        return data.currentCity.split(',')[0]
                    })
                }
                return;
            })
        }
    }

    $scope.getNames = function(value){
        getUsersFactory.getNames(value, function(data){
            if(data.code == 200)
            {
                $scope.names =
                data.response.map(function(data){
                    // $scope.names.push(data.firstName + ' ' + data.lastName);
                    return data.firstName + ' ' + data.lastName;
                });
            }
            else {
                console.log('something went wrong');
            }
        })
        return $scope.names;

    }



    $scope.page = function(page)
    {
        /* This function deals with pagination */
        var url ='';
        var getUrl = function(url){
            url.split('/').map(function(data, index){
                if(data == 'admin'){
                    url =  url.split('/').slice(index + 1).join('/')
                }

            })
            return url;
        }
        url = (page == 'next') ? getUrl($scope.users.info.next_page_url) : getUrl($scope.users.info.prev_page_url);

        url = '/' + url;
        /* This function loads the users for the next page */
        getUsersFactory.getUserInfo(function(data){
            $scope.users = users = data.response;
        }, url)
    }


}])




/*--------------------------------------------------------------------------------*/



/* -------------- Controller that gets info about a single user ------------------*/

myModule.controller('singleUserController', ['$scope', '$location', '$cookies', '$state', '$stateParams', 'getUsersFactory', 'singleUserFactory', 'fieldFactory', 'matchAssignFactory', function($scope, $location, $cookies, $state, $stateParams, getUsersFactory, singleUserFactory, fieldFactory, matchAssignFactory){

    $scope.singleUser = [];
    $scope.photos = [];
    $scope.colleges = [];
    $scope.values = [];
    $scope.imageUrls =[];
    $scope.file = undefined;
    $scope.message =undefined;
    $scope.index = $stateParams.index;
    $scope.unverifydata = {};
    $scope.button = ($stateParams.route == 'unapprovedUsers') ? "approve" : undefined;
    $scope.id = $location.path().split('/')[2];
    $scope.score = null;
    $scope.addCollege = {};
    $scope.chatInfo = {};
    $scope.show = false; // will be used to toggle the photo score button
    $scope.imageUrl = undefined; // stores the current image url
    $scope.mode = undefined; //this keeps track of the filter
    $scope.loading = true; // to keep track of laoder
    $scope.counter = 0; // counter to track counters for image filters
    $scope.path = undefined;// save the path of the picture to be edited
    $scope.name = undefined; // for name suggestion query of stack
    $scope.search = ($stateParams.id) ? $stateParams.id : undefined; // for stack search



    $scope.$on('foundChat', function(event, data){
        /* this function runs when we emit chat
        * it takes in 4 parameters
        * the last parameter is a function which is used for chat pagination
         */
        $scope.chatInfo = data[0];
        $scope.userA = data[1];
        $scope.userB = data[2];
        // $scope.chatPaginate = data[3];
        if(!$scope.chatInfo.message.data){
            $scope.getStatus($scope.userA, $scope.userB)
        }
        $('#person-chat').modal('show');
    })
    //set users if users are not set

    $scope.getData = function(){

        if($.isEmptyObject(users) && $stateParams.route == undefined && !$cookies.getObject('searchParams').show){
            /* This functions gets the first 100 users if users array is empty*/
            getUsersFactory.getUserInfo(function(data){
                if(data.code == 200){
                    users = data.response;
                }

            })
        }
            singleUserFactory.getSingleUser( $stateParams.id, function(data){
                $scope.singleUser = data;
                $scope.file = (data.user_name.gender && (data.user_name.gender == 'Male' || data.user_name.gender == 'male')) ? 'guy_images' : 'girls_images';
                $scope.id = $scope.singleUser.user_name.id;
            });
    }






    $scope.nextUser = function(){
        /* this function gets the id fo next user
        * This function takes in an optional parameter index
        */

        var execute = function(index = null){
            /* This function will be executed to get the next user data */
            if(users)
            {
                index = (index == null) ? $location.path().split('/').pop() : index;
                if((users.info == undefined) && (users.length > 0)){
                    // this if condition will execute when we come here from field controller
                     var nextId = (users[parseInt(index) + 1]) ? users[parseInt(index) + 1].id : undefined;
                }
                else {
                    var nextId =  (users.info.data[parseInt(index) + 1]) ? users.info.data[parseInt(index) + 1].userId : undefined;
                    // return;
                }
                if(nextId == undefined)
                {
                    if($stateParams.route != undefined){
                        /* if and when we dont require pagination and the admin comes from anyother page than allUsers */
                        var nextId = users[0].id
                        var index = -1;
                    }
                    else if ($stateParams.route == undefined && users.info.length != 100){
                        // when filters are applied on all users in the database
                        var nextId = users.info.data[0].userId
                        var index = -1;
                    }
                    else {
                        var url = '/' + users.info.next_page_url.split('/').pop();
                        getUsersFactory.getUserInfo(function(data){
                            if(data.code == 200){
                                users = data.response;
                            }
                            execute(-1);
                        }, url)
                    }


                }
                $state.go('singleUser', {'id': nextId, 'index': (parseInt(index) + 1)})

            }
            return;

        }

        if($.isEmptyObject(users) && ($stateParams.route != undefined)){
            /* If a user comes from a field page and then refreshes the page to get the field users data */
            users = fieldFactory.getAllUsers($stateParams.route, function(data){
                if(data.code == 200){
                    users = data.response;
                    execute();
                }
            })

        }
        else if(users.info || ($stateParams.route != undefined && users.length > 0)){
            // if users object exists and is coming from allUsers Page
            execute();
            $('.modal-backdrop').remove();
        }
        else {
            //redirects the user to the all users page
            $state.go('allUsers');
            $('.modal-backdrop').remove();
        }

        return;

    }

    $scope.getColleges = function(college){

     /* This function gets the colleges based on the search result */
      singleUserFactory.getColleges(college, function(data){
          if(data.code == 200){
              $scope.colleges = data.response;
          }
          else{
              console.log("something went wrong")
          }

     })
        return $scope.colleges;
    }






    $scope.updateCollegeScore = function(college, set = false){
        /* update college score */
        if(set == 'addCollege' && college== undefined){
            $scope.button = 'addCollege'
            return;
        }

        if(college == undefined){
            $scope.message = 'Please enter a college score';
            return;
        }
        singleUserFactory.updateCollegeScore($scope.id, college.collegeScore, function(data){
            if(data.code == 200)
            {
                $scope.button = null;
                $scope.message = 'College score updated to: ' + college.collegeScore;

            }

        })
        return;
    }

    $scope.chatPhotos = function(id=$scope.id, gender=$scope.singleUser.user_name.gender)
    {
        if(gender.charAt(0).toUpperCase() == 'M')
        {
            $scope.file = 'guy_images'
        }
        else{
            $scope.file = 'girls_images'
        }
        $scope.getPhotos(id);
        $('#person-chat').modal('hide');
        $('#modal1').modal('show');

    }


    $scope.getPhotos = function(id = $scope.id){
        /* This function gets all the photos for the user */
        if(id == $scope.id){
            $scope.file = ($scope.singleUser.user_name.gender && ($scope.singleUser.user_name.gender == 'Male' || $scope.singleUser.user_name.gender == 'male')) ? 'guy_images' : 'girls_images';
        }

        if($stateParams.route == undefined || $stateParams.route == 'unapprovedUsers'){
            $scope.show = true;
        }
        singleUserFactory.getAllPhotos(id, function(data){
            if(data.code == 200)
            {
                $scope.photos = data.response;
                $scope.photos.map(function(value, index){
                    // the first picture should be the profile picture
                    if(value.isDp == 1) {
                        var temp = $scope.photos[0]
                        $scope.photos[0] = $scope.photos[index];
                        $scope.photos[index] = temp;
                    }
                })
                for(var i = 0; i<= 9; i=i+0.5){
                    $scope.values.push(i);
                }
                $scope.removeAttr();
            }
        })
    }

    $scope.revese = false;
    $scope.orderByMe = function(x){
        $scope.reverse = ($scope.myOrderBy === x) ? !$scope.reverse : true;
        $scope.myOrderBy = x;

    }

    $scope.addPhotoScore = function(rating){
        /* This function adda photo score for the user with the specified id */
        if($scope.button == 'approve')
        {
            /* call the unverifyUser function when button is set to approve */
            $scope.unverifyUser(rating);
            return;
        }
        singleUserFactory.updatePhotoScore($scope.id, rating, function(data){
            if(data.code == 200)
            {
                $('.modal-backdrop').remove();


                if($stateParams.route == undefined){
                    /* This part will only execute when the user updates the photo score
                    * from allUsers page*/
                    $scope.nextUser();
                    $(function(){
                        setTimeout(function() {
                            $("#picture").trigger('click');
                        }, 10);
                    })
                }
                else if($stateParams.route == 'unapprovedUsers' && $scope.button == 'approve')
                {
                    $scope.unverifyUser();
                    // close the picture modal
                    $('#modal1').modal('hide');
                    $scope.message = 'Photo succesfully updated'
                }

            }
        })
    }
    $scope.close = function(modal){
        return $(modal).modal('hide');
    }



    /* ----- unverified users page ----*/
    $scope.verify = function(){
        singleUserFactory.verifyUser($scope.id, function(data){
            if(data.code == 200) {
                $scope.message = data.message;
                $(function(){
                    setTimeout(function(){
                        $('#picture').trigger('click');
                    }, 0)
                })

            }
        });
    }

     $scope.unverifyUser = function(rating=null){
        /* This function deals with both unverifying and blocking user*/

        if($scope.unverifydata.mainReason){
            $scope.unverifydata.reason = $scope.unverifydata.mainReason;
        }
            singleUserFactory.unverifyUser($scope.button, $scope.singleUser.user_name.id, $scope.unverifydata.reason, rating, function(data){
                if(data.code == 200){
                   if($scope.button == 'approve'){
                       $scope.close('#modal1');
                   }
                   else
                   {
                        $scope.close('#modal-unverify');
                   }

                   if(users.length){
                       users.splice($scope.index, 1);
                       $scope.nextUser();
                   }
                   else {
                       $state.go('allUsers');
                   }

                }
            })
    }


    /* ---- Reported Users page -----*/
    $scope.removeUser = function(){
        singleUserFactory.removeUser($scope.singleUser.user_name.id, function(data){
            if(data.code == 200)
            {
                $scope.close('#modal-remove');
            }
        })
    }

    $scope.getChat = function(url){
        /* This function gets the chat with a user */
        singleUserFactory.getChat(url, function(data){
            if(data.code == 200)
            {
                $scope.chats = data.response;
            }
        })
    }

    $scope.getStatus = function(userA, userB){
        singleUserFactory.getStatus(userA, userB, function(data){
            if(data.code == 200){
                $scope.status = data.response;


            $scope.status.userA.isLike = likeGenerator($scope.status.userA.isLike);
            if($scope.status.userB != null){
                $scope.status.userB.isLike = ($scope.status.userB.isLike) ? likeGenerator($scope.status.userB.isLike) : 'Not Seen';
            }



        }
    })
}


    $scope.removeAttr = function(){
        $('bxSlider li').removeAttr('bx-slider');
    }
    $scope.editImage = function(image){
        $scope.loading = true;
        $scope.imageUrl = image;
        singleUserFactory.saveImage(image, function(data){
            if(data.code == 200){
                $scope.path = 'images/' + data.response.split('/').pop();
                $('#canvas'+$scope.singleUser.user_name.gender.charAt(0).toUpperCase() ).removeAttr('data-caman-id');
                Caman('#canvas'+$scope.singleUser.user_name.gender.charAt(0).toUpperCase(), $scope.path + '?'+ escape(new Date()), function(){
                    this.resize({
                        width: 300,
                        height: 300
                    });
                    this.render();
                })
                // $('#modal1').css('height', '270%');
                $scope.loading = false;
            }
        })


    }


    $scope.filter = function(mode){
        /* This function applies the filters and changes the values based on number of clicks to create a switch effect */
        if($scope.mode == mode){
            $scope.counter++
        }
        else {
            $scope.counter = 0;
        }
        if($scope.imageUrl && $scope.path){
            if(mode =='refresh'){
                // refreshes the image and sets the counter back to 0
                Caman('#canvas'+$scope.singleUser.user_name.gender.charAt(0).toUpperCase(), $scope.path, function(){
                    this.revert();
                    this.render();
                })
                $('.btn-info').css('background-color', '#31b0d5');
                $scope.counter = 0
            }
            else if ($scope.counter % 2 == 0){
                Caman('#canvas'+$scope.singleUser.user_name.gender.charAt(0).toUpperCase(), $scope.path, function(){
                    this[mode]().render();
                })
                $('.'+ mode).css('background-color', 'green');

            }
            else if($scope.counter % 2 != 0){
                Caman('#canvas'+$scope.singleUser.user_name.gender.charAt(0).toUpperCase(), $scope.path, function(){
                    this.revert();
                    this.render();
                })
                $('.'+ mode).css('background-color', '#31b0d5');

            }
            $scope.mode = mode;
        }

    }


    $scope.saveFilteredImage = function(){
        var image = $('#canvas').get(0).toDataURL();
        singleUserFactory.saveFilteredImage($scope.id, $scope.imageUrl, $scope.path, image, function(data){
            console.log(data);
        })
    }

    $scope.makeProfilePicture = function(originalUrl){
        /* This function changes the users Profile Picture */
        url = originalUrl.split('/');
        if(url[2] == "s3-us-west-2.amazonaws.com") {
            url = url.pop();
        }
        else {
            url = url.join('/');
        }
        singleUserFactory.makeProfilePicture(url, $scope.id, function(data){
            if(data.code == 200){
                $scope.removeAttr();
                $scope.getPhotos();
                $scope.singleUser.user_name.dpUrl = originalUrl;
                $scope.message = "Profile Picture has been changed";
            }
        })
    }


    $scope.deletePhoto = function(originalUrl){
        url = originalUrl.split('/');
        if(url[2] == "s3-us-west-2.amazonaws.com") {
            url = url.pop();
        }
        else {
            url = url.join('/');
        }

        singleUserFactory.deletePhoto(url, $scope.id, function(data){
            if(data.code == 200){
                if($scope.photos.length){
                    $scope.message = "Picture has been delete";
                    $scope.photos.map(function(value, index){
                        if(value.imgUrl == originalUrl){
                            $scope.photos.splice(index, 1);
                        }
                    })
                }
            }
            else{
                $scope.message = data.message
            }
        })
    }

    $scope.checkEnter = function($event, object){

        if($event.keyCode == 13)
        {
            $scope.getStackData();
        }
    }

    $scope.assignValue = function(Aid=$scope.id, BId){
        console.log($scope.id);
        $scope.assign = {
            'userAId': $scope.id,
            'userBId': BId,
            'type': 'match'
        }
        $('#modal-assign').modal('show');
    }

    $scope.assignMatch = function(){
        $scope.assign = $scope.assign.userAId+"\t"+$scope.assign.userBId+"\t"+$scope.assign.type+"\n";
        matchAssignFactory.submitData($scope.assign, function(data){
            if(data.code == 200){
                $scope.stackData.map(function(value, index){
                    // delete from the stack data
                    if(value[0].userId == $scope.assign.split('\t')[1]){$scope.stackData.splice(index, 1)}
                    if(!$scope.stackData.length) {$scope.message = "No stack users"}
                })
                $('#modal-assign').modal('hide');
            }
            else {
                $scope.message = data.message;
            }
        })

    }

    $scope.getNames = function(value){
        getUsersFactory.getNames(value, function(data){
            if(data.code == 200)
            {
                $scope.names =
                data.response.map(function(data){
                    // $scope.names.push(data.firstName + ' ' + data.lastName);
                    return {'name': data.firstName + ' ' + data.lastName, 'id': data.id};
                });
            }
            else {
                console.log('something went wrong');
            }
        })
        return $scope.names;

    }


    $scope.getStackData = function(id = $stateParams.id){

        if(!id){
            id = $scope.search;
        }
        singleUserFactory.getStackData(id, function(data){
            if(data.code == 200 && data.response.length){
                $scope.stackData = data.response;
                $scope.$watch('stackData', function(){
                    $('.table-assign').fadeIn('slow');
                })
                return $scope.stackData
            }
            else{
                $scope.message = "No results for user";
            }
        })
    }
    if(!$stateParams.index){
        // we do this so that the message doesnt get stored for every page
        $scope.stackData = ($stateParams.id) ? $scope.getStackData() : undefined;
    }



}])

.directive('bxSlider', function($timeout){
    return {
            restrict: 'A',
            link: function (scope, element, attr) {
                /* This function will fire when the ng-repeat has funished */
                    if (scope.$last === true) {
                        $(function(){
                            setTimeout(function(){
                                // $('.bxSlider').bxSlider();
                                $('.bxSlider').bxSlider({
                                    'infiniteLoop': false,
                                    'controls': false,
                                });
                            }, 100)
                        })


                    }
                }
            }

})



/*--------------------------------------------------------------------------------*/
myModule.controller('fieldController', ['$scope', '$state', '$location', '$window', 'fieldFactory', function($scope, $state, $location, $window, fieldFactory){
    $scope.route = $location.path().split('/').pop();
    $scope.index = undefined;
    $scope.loading = true;
    $scope.userInfo = [];
    fieldFactory.getAllUsers($scope.route, function(data){
        if(data.code == 200){
            $scope.userInfo = users = data.response;
            $scope.totalUsers = totalUsers(users);
            $scope.loading = false;
        }
        else {
            console.log("something went wrong");
        }

    })



    $scope.goto= function(path){
        // this function simply takes us to the specified path
        var url = path.split('/');
        $state.go(url[0], {'id': url[1], 'index': url[2], 'route': url[3]});
    }


    /*this code snippet allows a title of a colum to be clicked to sort the data */
    $scope.revese = false;
    $scope.orderByMe = function(x){
        $scope.reverse = ($scope.myOrderBy === x) ? !$scope.reverse : true;
        $scope.myOrderBy = x;

    }

    $scope.filteredUsers = function(){
        $scope.totalUsers = totalUsers($scope.filteredResult);
    }


}])

/* ----------------------------------------------------------------*/

/*---------------- GRAPH CONTROLLER --------------------------------*/

myModule.controller('graphsController', ['$scope', '$window', 'graphsFactory', function($scope, $window, graphsFactory){



    var config = function(title, value1, value2){
        return {
                    title : {
                      text : title,
                      fontSize : 16,
                    },
                    legend: {},
                    'type' : 'line',
                    "scale-x":{
                      "label":{ /* Add a scale title with a label object. */
                        "text":"Time stamp",
                      },
                      'min-value': 1460437202000,
                      'step': 'day',
                      "transform":{ /* Converts your Unix timestamp to a human readable format. */
                        "type":"date", /* Set your transform type to "date". */
                        "all":"%m.%d.%y" /* Specify your date/time format, using tokens. */
                      }
                    },

                    series: [
                      { values : value1, text:  'Male' },
                      { values : value2, text: 'Female' }
                            ]
                };
    }

    $scope.ratios = function(route){
        graphsFactory.ratios(route, function(data){
            if(data.code == 200){
                $scope.params = data.response
                // $scope.params.sort(function(a, b){
                //     return b.created_at - a.created_at;
                // });

                // console.log($scope.params);

                $scope.myJson = {}
                $scope.maleData = [];
                $scope.femaleData = [];
                /* store data for various graphs in $scope.graph */
                if(route == 'getMaleFemaleRatio')
                {
                    $scope.params.map(function(data){
                        $scope.maleData.push(parseInt(data.totalM));
                        $scope.femaleData.push(parseInt(data.totalF));

                    })
                    // console.log($scope.maleData);
                    // console.log($scope.femaleData);

                }

                if(route == 'getActiveMaleFemaleRatio')
                {
                    $scope.params.map(function(data){
                        $scope.maleData.push(parseInt(data.activeM));
                        $scope.femaleData.push(parseInt(data.activeF))
                    })

                }

                if(route == 'getNewMaleFemaleRatio')
                {
                    $scope.params.map(function(data){
                        $scope.maleData.push(parseInt(data.newM));
                        $scope.femaleData.push(parseInt(data.newF))
                    })
                }

                if(route == 'getMutualLikeRatio')
                {
                    $scope.params.map(function(data){
                        $scope.maleData.push(parseInt(data.mutualLikeM));
                        $scope.femaleData.push(parseInt(data.mutualLikeF));
                    })
                }

                if(route == "getavTimeFirstMatchRatio")
                {
                    $scope.params.map(function(data){
                        $scope.maleData.push(parseInt(data.avTimeFirstMatchM));
                        $scope.femaleData.push(parseInt(data.avTimeFirstMatchF));
                    })
                }

                if(route == "getavTimeMatchRatio")
                {
                    $scope.params.map(function(data){
                        $scope.maleData.push(parseInt(data.avTimeMatchM));
                        $scope.femaleData.push(parseInt(data.avTimeMatchF));
                    })
                }
                // console.log($scope.maleData);
                // console.log($scope.femaleData);

                $scope.myJson = config('Male Female Ratio', $scope.maleData, $scope.femaleData);
                // $scope.myJson = config('Male Female Ratio', , $scope.femaleData);


            }
        })
    }
}])

/*----------------- Chats controller -------------*/

myModule.controller('chatsController', ['$scope', '$state', '$stateParams', '$cookies', 'chatsFactory', function($scope, $state, $stateParams, $cookies, chatsFactory){
    $scope.userA = undefined;
    $scope.userB = undefined;
    $scope.loading = true;
    $scope.show = ($stateParams.type == 0) ? true : false;

    $scope.getData = function(page=0){
        chatsFactory.getData($stateParams.type, page, function(data){
            if(data.code == 200){
                $scope.chatData = data.response;
                $scope.loading = false;
            }
        })
    }


    $scope.chatPaginate = function(page=0){
        url = 'getChatInfo/' + $scope.userA + '/' + $scope.userB;
        chatsFactory.getChat(url, $scope.type,  page, function(data){
            if(data.code == 200){
                $scope.chatInfo.message = data.response.message;
                $scope.chatInfo.nextPage = data.response.nextPage;
                $scope.chatInfo.prevPage = data.response.prevPage;
            }
        })
    }

    $scope.getChatInfo = function(Aid, Bid, url='getChatInfo'){
        $scope.Aid = Aid;
        $scope.Bid = Bid;
        if(url == 'getChatInfo'){
            url = url + '/' + Aid + '/' + Bid;
            $scope.userA = Aid;
            $scope.userB = Bid;
        }
        if($state.current.name == 'chats'){
            /* the user is coming from all users page
            * will need chats of both type 0 and 1
            */
            $scope.type = $stateParams.type;
        }
        else if($state.current.name == 'singleUser') {
            $scope.type = [0, 1];
        }
        chatsFactory.getChatInfo(url, $scope.type, function(data){
            if(data.code == 200)
            {
                if(data.response.message.data){
                    data.response.message.data = data.response.message.data.sort(function(a, b) {
                        return a.date - b.date;
                    });
                }
                 $scope.chatInfo = data.response;
                 if($stateParams.route == undefined){
                     $scope.$emit('foundChat', [$scope.chatInfo, $scope.userA, $scope.userB]);
                 }
            }

        })

    }
}])


/*---- Activity Controller ----*/
myModule.controller('activityController', ['$scope', '$state', '$cookies', 'activityFactory', function($scope, $state, $cookies, activityFactory){
    $scope.loading = true;
    $scope.likeGenerator = likeGenerator;
    $scope.chatInfo = {};
    if($cookies.getObject('activityFilter') == undefined){
        $cookies.putObject('activityFilter', {'isLike': []});
    }
    $scope.filter = $cookies.getObject('activityFilter');
    $scope.users = {};
    $scope.getData = function(info = $scope.filter, page=0){
        if(!info.period){
            info.period = 'all';
        }
        activityFactory.getData(info, page, function(data){
            if(data.code == 200){
                if(data.response.data.length == 0){
                    $scope.message = "no data has been found";
                }
                else {
                    $scope.users = data.response;
                }
                $(function(){
                    $scope.loading = false;
                    $('.dropdown-menu').slideUp('slow');
                })


            }
        })
    }

    $scope.dateFinder = function(){
        if($scope.filter){
            if($scope.filter.start && $scope.filter.end){
                var period = {'start': $scope.filter.start.getFullYear() + '-' + ($scope.filter.start.getMonth() + 1) + '-' + $scope.filter.start.getDate(),
                                'end': $scope.filter.end.getFullYear() + '-' + ($scope.filter.end.getMonth() + 1) + '-' + $scope.filter.end.getDate()
                            }
                $scope.filter.period = period;
                // delete these values no point sending extra data to back end
                delete $scope.filter.start;
                delete $scope.filter.end;
                $scope.getFilteredData(period);
            }
        }

    }

    $scope.$watch('filter.isLikeA', function(){
        $scope.filter.isLike.push($scope.filter.isLikeA);
    })


    $scope.getIsLikes = function(likes){
            // this function generates the names of all the likes
            var data = likes.map(function(data, index){
                if(data){
                    return $scope.likeGenerator(data);
                }
                else {
                    likes.splice(index, 1);
                }
            })

            return data.toString();
    }

    $scope.getFilteredData = function(){
                /* This function queries the database based on different filter */
        if($scope.filter.isLike){
            // remove extra data
            delete $scope.filter.isLikeA;
        }
        $cookies.putObject('activityFilter', $scope.filter);
        $scope.getData($scope.filter);
    }

    $scope.refreshData = function(){
            $cookies.putObject('activityFilter', {'isLike': []});
            $state.go($state.current, {}, {reload: true});
    }

    $scope.singleUserData = function(info){
        activityFactory.getChatInfo(info, function(data){
            if(data.code == 200){
                $scope.chatInfo = data.response;
                for(var i =0, n=$scope.users.data.length; i< n; i++){
                    if($scope.users.data[i].userAId == info.userAId){
                        $scope.chatInfo.isLikeUserA = $scope.likeGenerator($scope.users.data[i].isLike);
                    }
                    if($scope.users.data[i].userBId == info.userBId ){
                        $scope.chatInfo.isLikeUserB = ($scope.users.data[i].isLikeA_B) ? $scope.users.data[i].isLikeA_B[0].isLike : 'Not Seen';
                    }
                }
                $('#person-chat').modal('show');
            }
        })

    }



}])

/*------------- Notifications controller ------------*/
myModule.controller('notificationController', ['$scope', '$stateParams', '$state', '$cookies', '$window', 'Upload', 'notificationFactory',  function($scope, $stateParams, $state, $cookies, $window, Upload, notificationFactory){
    $scope.loading = true;
    $scope.notifiactions = undefined;
    $scope.add = undefined;
    $scope.message = false;
    $scope.info = $cookies.getObject('info');


    $scope.getData = function(){
        notificationFactory.getData(function(data){
            //get all the notifications
            if(data.code == 200){
                $scope.notifications = data.response;

                $scope.loading = false;
            }
        })
    }

    $scope.upload = function (data) {
    var token=angular.fromJson($window.localStorage.getItem("access_token"));
       Upload.upload({
           url: 'api/v1/admin/addNotification?token=' + token,
           fields: {'type': data.type, 'date': data.dt, 'title': data.title, 'description': data.description},
           file: data.image
       }).then(function (resp) {
           if(resp.code == 200){ $state.go('notification')}
       }, function (resp) {
           console.log('Error status: ' + resp.status);
       }, function (evt) {
           var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
           console.log('progress: ' + progressPercentage + '% ' + evt.config.file.name);
       });
   };

   $scope.$watch('add.date', function(){
       $('.dropdown-menu').fadeToggle('slow');
   })
    $scope.addNotification = function(){
            if($scope.add.date){
                $scope.add.date = ($scope.add.date.getFullYear() + '-' + ($scope.add.date.getMonth() + 1) + '-' + $scope.add.date.getDate());
            }


        if($scope.add.type && $scope.add.description && $scope.add.title){
            if($scope.add.image){
                $scope.upload($scope.add);

            }
            else{
                notificationFactory.addNotification($scope.add, function(data){
                    console.log(data);
                    if(data.code == 200){
                        $state.go('notification');
                    }
                })
            }


        }
        else {
            $scope.message = "please fill all the fields";
        }

    }

    $scope.editNotification = function(info=null){
        if($state.current.name == 'notification' && info){
            $cookies.putObject('info', info)
             $scope.info = info;
         }
        else if($scope.info == undefined){
            // this condition will be true if a user refreshes the page
            $state.go('notification');
        }
        else if($state.current.name == 'edit' && $scope.info != undefined){
            $scope.info.date = ($scope.info.date.getFullYear() + '-' + ($scope.info.date.getMonth() + 1) + '-' + $scope.info.date.getDate());
            // send data to the factory to edit the notification
            notificationFactory.editNotification($scope.info, function(data){
                if(data.code == 200){
                    $cookies.putObject('info', {});
                    $state.go('notification');
                }
            })
        }
    }

    $scope.deleteNotification = function(id, index){
        if(id){
            notificationFactory.deleteNotification(id, function(data){
                if(data.code == 200){ $scope.notifications.splice(index, 1)}
            })
        }
    }
}])
.directive('fileModel', ['$parse', function($parse){
    return {
        restrict: 'A',
        link: function(scope, element, attrs){
            var model = $parse(attrs.fileModel);
            var modelSetter = model.assign;

            element.bind('change', function(){
                scope.$apply(function(){
                    modelSetter(scope, element[0].files[0]);
                })
            })
        }

    }
}])


/* --------- Match Assing Controller -----------*/
myModule.controller('matchAssignController', ['$scope', 'matchAssignFactory', function($scope, matchAssignFactory){
    $scope.matchAssign=undefined;
    $scope.message = undefined;
    $scope.reverse = false;
    $scope.myOrderBy = undefined;
    $scope.matchAssignData = undefined;

    $scope.getMatchAssign = function(page=0, x = undefined){
        var reverse;
        if(x){
            // $scope.reverse = (x === $scope.myOrderBy) ? !$scope.reverse : false;
            $scope.reverse  = !$scope.reverse;

        }

        if($scope.reverse == true){
            reverse = 'asc'
        }
        else {
            reverse = 'desc'
        }
        var data = {'orderBy': 'updated_at', 'reverse': reverse, 'page': page};
        matchAssignFactory.getMatchAssign(data, function(data){
            if(data.code == 200){
                $scope.matchAssignData = data.response;
            }
            else {
                $scope.message = data.message;
            }
        })
    }

    $scope.submitData = function(){
        matchAssignFactory.submitData($scope.matchAssign, function(data){
            $scope.message = data.message
        })
    }
}])
