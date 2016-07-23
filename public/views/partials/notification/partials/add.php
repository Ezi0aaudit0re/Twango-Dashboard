<!-- A form to add Notifications -->
<div ng-controller='notificationController'>
    <h1 style='text-align: center'>Add Notification</h1>
    <h4 style='text-align: center; color: #337ab7'><u>Type: &nbsp{{add.type}}</u></h4>
    <div class="form-container">

        <form  method="post" enctype="multipart/form-data"><br>
            <div class="dropdown">
                  <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" style='width: 200px'>{{(add.date) ? add.date.getFullYear() + '-' + (add.date.getMonth() +1) + '-' + add.date.getDate() : 'Add Date'}}
                  <span class="caret"></span></button>
                  <div class="dropdown-menu" style='margin-left: 50px;'>
                      <uib-datepicker ng-model="add.date" class="well well-sm" datepicker-options="options"></uib-datepicker>
                  </div>
            </div>
            <div class="notification">
                <select ng-model='add.type' ng-init="add.type='match'">
                    <option value='match' selected >Match</option>
                    <option value='mutualLike'>Mutual Like</option>
                    <option value='chatEnded'>Chat Ended</option>
                </select><br>
                <input type="text" ng-model='add.title' placeholder='Title' /><br>
                <input type="text" ng-model='add.description' placeholder='Description' /><br>
                <input class="button" ngf-select ng-model="add.image" name="file" ngf-pattern="'image/*'"
                   ngf-accept="'image/*'" ngf-max-size="20MB" ngf-min-height="100"
                   ngf-resize="{width: 100, height: 100}" type='file' placeholder='Select Image' />

                <!-- <input type="file" placeholder='Select Image' file-model='add.image'  /><br> -->
                <button type="button" class='btn btn-success' ng-click='addNotification()' >Add Notification</button>
            </div>

        </form>


        </form>
    </div>


    <div class="alert alert-danger" ng-show='message' style='text-align: center; width: 300px; margin: 0 auto'>
        {{message}}
    </div>
    <script type="text/javascript">
        $('.dropdown-toggle').click(function(){
            $('.dropdown-menu').fadeToggle('slow');
        })

    </script>
</div>
