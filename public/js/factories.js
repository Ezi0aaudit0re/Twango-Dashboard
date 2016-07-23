var myModule = angular.module('myApp');

var prefix = 'api/v1/admin';
var urlMaker = function(url, token)
{
    if(url.indexOf('?') != -1)
    {
        url = url + '&&token=' + token;
    }
    else {
        url = url + '?token=' + token;
    }
    return url;
}

myModule.factory('getUsersFactory', function($http, $window){
    /* This factory get all the users info */
    var factory = {};
    var token="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjMsImlzcyI6Imh0dHA6XC9cL3l1cnJheS5jb21cL2FwaVwvdjFcL2FkbWluXC9sb2dpbiIsImlhdCI6MTQ2Njg4ODA4OCwiZXhwIjoxNDY5MzA3Mjg4LCJuYmYiOjE0NjY4ODgwODgsImp0aSI6IjMwNDAzMTYyMTRmOTAxMDg3ZWI0OTM1MWQ5Mzc3ZTQ1In0.79xQ4Zy5igV_vD3mkYWp2XMtaS6jK5XbbP3YoIs6vs0"
    factory.getUserInfo = function(callback, url='/allUsers'){
         prefix = 'api/v1/admin'
        $http.get(prefix  + urlMaker(url, token)).success(function(data){
                callback(data);
            })

    }



    factory.sortDataBy = function(orderBy=null, order=null, callback){
        /* This function sorts the entire data */
        if(orderBy && order != null){
            $http.get(prefix + '/sortAllData/' + orderBy + '/' + order + '?token='+token).success(function(data){
                callback(data);
            })
        }
    }

    factory.getCities = function(callback){
        $http.get(prefix + '/getCities?token=' + token ).success(function(data){
            callback(data);
        })
    }

    factory.getNames= function(value, callback){
        $http.get(prefix + '/getNames?token=' + token, {
            params: {
                value: value,
            }
        }).success(function(data){
            callback(data);
        })
    }

    factory.getSearchedData = function(object, callback){
        // console.log(object);
        // return;
        $http.get(prefix + '/getSearchedData?token=' + token, {
            params: {
                'object': object,
            }
        }).success(function(data){
            callback(data);
        })
    }

    return factory;
});
/* ---- Factory that makes an AJAX request to get info about single user -----*/
myModule.factory('singleUserFactory', function($http, $window){
    var factory = {};
    var token="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjMsImlzcyI6Imh0dHA6XC9cL3l1cnJheS5jb21cL2FwaVwvdjFcL2FkbWluXC9sb2dpbiIsImlhdCI6MTQ2Njg4ODA4OCwiZXhwIjoxNDY5MzA3Mjg4LCJuYmYiOjE0NjY4ODgwODgsImp0aSI6IjMwNDAzMTYyMTRmOTAxMDg3ZWI0OTM1MWQ5Mzc3ZTQ1In0.79xQ4Zy5igV_vD3mkYWp2XMtaS6jK5XbbP3YoIs6vs0"

    factory.getSingleUser = function(id, callback){
        $http.get('api/v1/admin/singleUser/' + id + '?token=' + token).success(function(data){
            callback(data);
        })
    }

    // factory to get photos of the users
    factory.getAllPhotos = function(id, callback){
        $http.get('api/v1/admin/profilePhotos/' + id + '?token=' + token).success(function(data){
            callback(data);
        })
    }

    factory.getStatus = function(userA, userB, callback){
        $http.get('api/v1/admin/getStatus', {
            params: {
                'token': token,
                'Aid': userA,
                'Bid': userB
            }
        }).success(function(data){
            callback(data);
        })
    }



    // function to get colleges
    factory.getColleges = function(college, callback){
        $http.get('api/v1/admin/allColleges?token=' + token, {
            params: {
                collegeName: college
            }
        }).success(function(data){
            callback(data)
        })
    }

    //update college score
    factory.updateCollegeScore = function(id, score, callback){
        $http.get('api/v1/admin/updateCollegeScore/' + id + '/' + score + '?token='+token).success(function(data){
            callback(data);
        })
    }

    factory.updatePhotoScore = function(id, score, callback){
        $http.get('api/v1/admin/updatePhotoScore/' + id + "/" + score +'?token=' +token).success(function(data){
            callback(data);
        })
    }

    /*----- unverified users page -------*/
    factory.verifyUser = function(id, callback){
        $http.put('api/v1/admin/verifyuser/' + id + '?token=' + token).success(function(data){
            callback(data);
        })
    }

    factory.unverifyUser = function(button, id, reason, rating, callback){

        if(button == 'unverify'){
            $http.put('api/v1/admin/unverifyuser?token=' + token, {'id': id, 'reason': reason})
            .success(function(data){
                callback(data);
            })
        }
        else if(button=='block'){
            /* TODO still not working */
            $http.put('api/v1/admin/blockuser?token=' + token, {'id': id, 'reason': reason})
            .success(function(data){
                callback(data);
            })

        }
        else if (button =='dissaprove'){
            /* TODO method not allowed error */

            $http.put('api/v1/admin/disapproveuser?token=' + token, {'id': id, 'reason': reason})
            .success(function(data){
                callback(data);
            })
        }
        else if (button =='approve'){
            /* TODO interternal server error */

            $http.put('api/v1/admin/approveuser?token=' + token, {'id': id, 'photoScore': rating})
            .success(function(data){
                callback(data);
            })
        }
        else if (button == 'waitlist'){
            $http.put('api/v1/admin/waitlistUser?token=' + token, {'id': id})
            .success(function(data){
                callback(data);
            })
        }

    }

    factory.removeUser = function(id, callback){
        /* This function removes the user from reported page */
        $http.put('api/v1/admin/removeuserfromreported?token=' +token, {'id': id})
        .success(function(data){
            callback(data);
        })
    }

    factory.getChat = function(url, callback){
        $http.get('api/v1/admin/' + url + '?token=' +token)
        .success(function(data){
            callback(data);
        })
    }

    factory.makeProfilePicture = function(url, id, callback){
        $http.put('api/v2/admin/makeProfilePicture?token=' + token, {'id': id, 'url': url})
        .success(function(data){
            callback(data);
        })
    }

    factory.deletePhoto = function(url, id, callback){
        $http.delete('api/v2/admin/deletePhoto', {
            params: {id: id, url: url, token: token}
        })
        .success(function(data){
            callback(data);
        })
    }

    factory.saveImage = function(image, callback){
        /* This function saves the image to the temporary directory  */
        $http.get('api/v2/admin/saveImage', {
            params: {
                'token': token,
                'image': image
            }
        })
        .success(function(data){
            callback(data);
        })
    }

    factory.saveFilteredImage = function(id, url, image, path, callback){
        /* THis function saves the image with a filter */
        $http.put('api/v2/admin/saveFilteredImage?token='+token, {'id': id, 'url': url, 'path': path, 'image': image})
        .success(function(data){
            callback(data);
        })
    }

    factory.getStackData = function(searchParams, callback){
        $http.get('api/v2/admin/stack', {
            params: {
                'token': token,
                'searchParams': searchParams
            }
        })
        .success(function(data){
            callback(data);
        })
    }


    return factory;
})

/* ---------------------------------------------------------------------------*/


/* ----------------- THis factory gets Information from a particular field ----*/
myModule.factory('fieldFactory', function($http, $window){
    var factory = {};
    var token="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjMsImlzcyI6Imh0dHA6XC9cL3l1cnJheS5jb21cL2FwaVwvdjFcL2FkbWluXC9sb2dpbiIsImlhdCI6MTQ2Njg4ODA4OCwiZXhwIjoxNDY5MzA3Mjg4LCJuYmYiOjE0NjY4ODgwODgsImp0aSI6IjMwNDAzMTYyMTRmOTAxMDg3ZWI0OTM1MWQ5Mzc3ZTQ1In0.79xQ4Zy5igV_vD3mkYWp2XMtaS6jK5XbbP3YoIs6vs0"

    factory.getAllUsers = function(url, callback){

        $http.get('api/v1/admin/' + url + '?token=' + token).success(function(data){

            callback(data);
        })
    }

    return factory;
})



/* -------- Factory for graphs ------------------*/

myModule.factory('graphsFactory', function($http, $window){
    var factory = {};
    var token="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjMsImlzcyI6Imh0dHA6XC9cL3l1cnJheS5jb21cL2FwaVwvdjFcL2FkbWluXC9sb2dpbiIsImlhdCI6MTQ2Njg4ODA4OCwiZXhwIjoxNDY5MzA3Mjg4LCJuYmYiOjE0NjY4ODgwODgsImp0aSI6IjMwNDAzMTYyMTRmOTAxMDg3ZWI0OTM1MWQ5Mzc3ZTQ1In0.79xQ4Zy5igV_vD3mkYWp2XMtaS6jK5XbbP3YoIs6vs0"

    factory.ratios = function(route, callback){
        $http.get('api/v1/admin/' + route + '?token=' + token).success(function(data){
            callback(data);
        })
    }

    return factory;
})


/* ------ Chats Factory   ----*/
myModule.factory('chatsFactory', function($http, $window){
    var factory = {};
    var token="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjMsImlzcyI6Imh0dHA6XC9cL3l1cnJheS5jb21cL2FwaVwvdjFcL2FkbWluXC9sb2dpbiIsImlhdCI6MTQ2Njg4ODA4OCwiZXhwIjoxNDY5MzA3Mjg4LCJuYmYiOjE0NjY4ODgwODgsImp0aSI6IjMwNDAzMTYyMTRmOTAxMDg3ZWI0OTM1MWQ5Mzc3ZTQ1In0.79xQ4Zy5igV_vD3mkYWp2XMtaS6jK5XbbP3YoIs6vs0"

    factory.getData= function(type=0, page, callback){
        /* This function data gets chats for all the users */
        $http.get('api/v1/admin/allChats', {
            params: {
                'type': type,
                'token': token,
                "page": page,
            }
        }).success(function(data){
            callback(data);
        })
    }


    factory.getChatInfo = function(url, type, callback){
        $http.get('api/v1/admin/' + urlMaker(url, token), {
                params: {
                type: type
            }
        }).success(function(data){
            callback(data);
        })
    }

    factory.getChat = function(url, type, page, callback){
        // This function helps paginate chat
        $http.get('api/v1/admin/' + urlMaker(url, token), {
            params: {
                'type': type,
                'page': page

            }
        }).success(function(data){
            callback(data);
        })
    }

    return factory;
})


/* ------------- Activity Factory ------*/
myModule.factory('activityFactory', function($http){
    var token="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjMsImlzcyI6Imh0dHA6XC9cL3l1cnJheS5jb21cL2FwaVwvdjFcL2FkbWluXC9sb2dpbiIsImlhdCI6MTQ2NzI4MzAwNywiZXhwIjoxNDY5NzAyMjA3LCJuYmYiOjE0NjcyODMwMDcsImp0aSI6IjUwNTRhNjA4YTQ5ZGY4MzM0YzI5M2VmMTFiMWVlNGU2In0.q47h2EVoBRC7k2gsVpsfGiI7CqaapDMHq5XIQq0b0mU";
    var factory = {};
        factory.getData = function(info, page, callback){
            $http.get('api/v2/admin/activity', {
                params: {
                    "token": token,
                    "page": page,
                    "info": info
                }
            }).success(function(data){
                callback(data);
            })
        }

        factory.getChatInfo = function(data, callback){
            $http.get('api/v1/admin/getChatInfo/'+ data.userAId + '/' + data.userBId, {
                params: {
                    "token": token,
                    "getStatus": true
                }
            } )
            .success(function(data){
                callback(data);
            })
        }
    return factory;
})


/*------------- Notification Factory -------------*/
myModule.factory('notificationFactory', function($http){
    var factory = {};
    var token="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjMsImlzcyI6Imh0dHA6XC9cL3l1cnJheS5jb21cL2FwaVwvdjFcL2FkbWluXC9sb2dpbiIsImlhdCI6MTQ2Njg4ODA4OCwiZXhwIjoxNDY5MzA3Mjg4LCJuYmYiOjE0NjY4ODgwODgsImp0aSI6IjMwNDAzMTYyMTRmOTAxMDg3ZWI0OTM1MWQ5Mzc3ZTQ1In0.79xQ4Zy5igV_vD3mkYWp2XMtaS6jK5XbbP3YoIs6vs0";
    factory.getData = function(callback){
        $http.get('api/v1/admin/allNotifications', {
            params: {
                "token": token,
            }
        }).success(function(data){
            callback(data);
        })
    }

    factory.addNotification = function(info, callback){
        $http.post('api/v1/admin/addNotification?token=' + token, info)
        .success(function(data){
            callback(data);
        })
    }

    factory.deleteNotification = function(id, callback){
        $http.get('api/v1/admin/deleteNotification', {
            params: {
                'token': token,
                'id': id
            }
        })
        .success(function(data){
            callback(data);
        })
    }

    factory.editNotification = function(info, callback){
        $http.put('api/v1/admin/editNotification?token=' + token, info)
        .success(function(data){
            callback(data);
        })
    }

    return factory;
})

/* ----------- Match Assign factory ------------*/
myModule.factory('matchAssignFactory', function($http, $window){
    var factory = {};
    var token="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjMsImlzcyI6Imh0dHA6XC9cL3l1cnJheS5jb21cL2FwaVwvdjFcL2FkbWluXC9sb2dpbiIsImlhdCI6MTQ2Njg4ODA4OCwiZXhwIjoxNDY5MzA3Mjg4LCJuYmYiOjE0NjY4ODgwODgsImp0aSI6IjMwNDAzMTYyMTRmOTAxMDg3ZWI0OTM1MWQ5Mzc3ZTQ1In0.79xQ4Zy5igV_vD3mkYWp2XMtaS6jK5XbbP3YoIs6vs0";

    factory.submitData = function(data, callback){
        $http.put('/api/v2/admin/matchAssign?token='+token, {
            'data': data
        })
        .success(function(data){
            callback(data);
        })
    }

    factory.getMatchAssign = function(info, callback){
        $http.get('/api/v2/admin/getMatchAssign', {
            params:{
                'token': token,
                'orderBy': info.orderBy,
                'reverse': info.reverse,
                'page': info.page
            }
        })
        .success(function(data){
            callback(data);
        })
    }
    return factory;
})
