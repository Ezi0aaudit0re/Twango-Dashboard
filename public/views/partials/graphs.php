<div ng-controller='graphsController' >
    <a class='btn btn-danger' ng-click='ratios("getMaleFemaleRatio")'>MALE : FEMALE</a>
    <a type="button" class='btn btn-info' ng-click='ratios("getActiveMaleFemaleRatio")'>ACTIVE MALE : FEMALE</a>
    <button type="button" class='btn btn-danger' ng-click='ratios("getNewMaleFemaleRatio")'>NEW MALE : FEMALE</button>
    <button type="button" class='btn btn-info' ng-click='ratios("getMutualLikeRatio")'>MUTUAL LIKE MALE : FEMALE</button>
    <button type="button" class='btn btn-danger' ng-click='ratios("getavTimeFirstMatchRatio")'>Active first match</button>
    <button type="button" class='btn btn-success' ng-click='ratios("getavTimeMatchRatio")'>active match</button><br>
    <zingchart id="myChart" zc-json="myJson" zc-height=500 zc-width=600></zingchart>
</div>
