<!-- A particular notification is edited -->
<div ng-controller='notificationController'>
    <h1 style='text-align: center'>Edit Notification for id: {{info.id}}</h1>
    <h4 style='text-align: center; color: #337ab7'><u>Type: &nbsp{{info.type}}</u><i style='margin-left: 10%'>Date:&nbsp{{info.date}}</i></h4>
    <div class="form-container">
        <div class="dropdown">
              <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" style='width: 200px'>Add Date
              <span class="caret"></span></button>
              <div class="dropdown-menu">
                  <uib-datepicker ng-model="info.date" class="well well-sm" datepicker-options="options"></uib-datepicker>
              </div>
        </div>
        <form class="notification"  method="post" enctype='multipart/form-data' ><br>
            <select ng-model='info.type'>
                <option  selected value='match'>Match</option>
                <option value='mutualLike'>Mutual Like</option>
                <option value='chatEnded'>Chat Ended</option>
            </select><br>
            <input type="text" ng-model='info.title'/><br>
            <input type="text" ng-model='info.description'  /><br>
            <input class="button" ngf-select ng-model="info.image" name="file" ngf-pattern="'image/*'"
               ngf-accept="'image/*'" ngf-max-size="20MB" ngf-min-height="100"
               ngf-resize="{width: 100, height: 100}" type='file' placeholder='Select Image' />
            <button type="button" class='btn btn-success' ng-click='editNotification()' >Edit Notification</button>
        </form>
    </div>
    <script type="text/javascript">
        $('.dropdown-toggle').click(function(){
            $('.dropdown-menu').fadeToggle('slow');
        })

    </script>
</div>
