<div ng-controller='singleUserController'>
    <style media="screen">
        #name{
            width: 300px;
            margin: 10px auto;
            padding: 10px;
        }

    </style>
    <div class="search" ng-hide='stackData'>
        <input id='name' uib-typeahead='name.id as name.name for name in getNames($viewValue)' typeahead-show-hint="true" typeahead-min-length="2" placeholder='Enter Name or ID' ng-model='search' ng-keydown='checkEnter($event, search )' />
    </div>
    <div ng-show="!stackData.length && message" style="text-align: center; width: 300px; margin: 0 auto">
        <h3 class="alert alert-danger" >{{message}}</h3>
    </div>
    <div class='table-assign'>
        <table class='table'>
            <thead>
                <tr>
                    <th>BId</th>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Age</th>
                    <th>City</th>
                    <th>Purchased</th>
                    <th>Spent</th>
                    <th>Last Active</th>
                    <th>Registered</th>
                    <th>Compatibility</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat='data in stackData'>
                    <td>{{data[0].userId}}</td>
                    <th>{{data[0].user.firstName + ' ' + data[0].user.lastName}}</th>
                    <th>{{data[0].gender.charAt(0).toUpperCase()}}</th>
                    <th>{{data[0].age}}</th>
                    <th>{{data[0].currentCity}}</th>
                    <th>{{data[0].currencyPurchased}}</th>
                    <th>{{data[0].currencyUsed}}</th>
                    <th>{{data[0].lastActiveDate}}</th>
                    <th>{{data[0].created_at}}</th>
                    <th>{{data[0].compatibilities[0].compatibilityAB}}</th>
                    <th><span class='btn btn-info' ng-click='assignValue(40, data[0].userId)'>Assign</span></th>
                </tr>
            </tbody>
        </table>
    </div>

    <div class='modal fade' id='modal-assign'>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class='close' data-dismiss='modal'>&times;</button>
                <h3>Assign</h3>
            </div>
            <div class="modal-body" >
                <div ng-show='message'>
                    {{message}}
                </div>
                UserA<input type="text" ng-model='assign.userAId' placeholder='User AId'/>
                UserB<input type="text" ng-model='assign.userBId' placeholder='User BId'/>
                <select ng-model='assign.type'>
                    <option value='match'>Match</option>
                    <option value='extraMatch'>Extra Match</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class='btn btn-success' ng-click='assignMatch()'>Submit</button>
            </div>

        </div>

    </div>
    <script type="text/javascript">
        $('.table-assign').hide();
    </script>
</div>
