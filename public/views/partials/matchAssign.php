<div ng-controller='matchAssignController' class='container' ng-init='getMatchAssign()'>
    <div class="row" ng-show='!message'>
        <div class="form">
            <textarea ng-model='matchAssign' rows="7" cols="100" placeholder='Match Assign Data'></textarea>
        </div>
        <button type="button" class='btn btn-success' ng-click='submitData()'>Submit</button>

    </div>
    <hr style='broder-bottom: 3px solid green'>
    <div class="alert alert-warning" ng-show='message' style='text-align: center'>
        <h1>{{message}}</h1>
    </div>
    <div class="row" style='margin-top: 10px'>
        <table class='table'>
            <thead>
                <tr>
                    <th>UserAId</th>
                    <th>UserBId</th>
                    <th ng-click='getMatchAssign(matchAssignData.next_page_url - 1, "updated_at")'>Assigned At</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat='data in matchAssignData.data'>
                    <td>{{data.userAId}}</td>
                    <td>{{data.userBId}}</td>
                    <td>{{data.updated_at}}</td>
                </tr>
            </tbody>
        </table>
        <div class="pagination-button">
            <button type="button" class='btn btn-info' ng-show='matchAssignData.prev_page_url != null' ng-click='getMatchAssign(matchAssignData.prev_page_url)'>Prev</button>&nbsp
            <button type="button" class='btn btn-info' ng-show='matchAssignData.next_page_url' ng-click='getMatchAssign(matchAssignData.next_page_url)'>Next</button>
        </div>
    </div>

</div>
