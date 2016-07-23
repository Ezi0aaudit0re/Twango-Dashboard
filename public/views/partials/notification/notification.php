<div ng-controller='notificationController' ng-init="getData()">
    <div style='text-align: center'>
        <button class='btn btn-success' ui-sref='add' style='width: 300px; font-size: 30px; margin: 10px auto;'>Add</button>
    </div>



    <h1>All Notifications</h1>
    <hr>
    <table class='table'>
        <thead>
            <tr>
                <th>
                    ID
                </th>
                <th>
                    Type
                </th>
                <th>
                    Title
                </th>
                <th>
                    Description
                </th>
                <th>
                    Date
                </th>
                <th>
                    Action
                </th>
            </tr>
        </thead>
        <tbody>
            <tr ng-repeat='notification in notifications'>
                <td>{{notification.id}}</td>
                <td>{{notification.type}}</td>
                <td>{{notification.title}}</td>
                <td>{{notification.description}}</td>
                <td>{{notification.date}}</td>
                <td>
                    <button class='btn btn-warning' ui-sref='edit' ng-click='editNotification(notification)'>Edit</button>
                    <button class='btn btn-danger' ng-click='deleteNotification(notification.id, notifications.indexOf(notification))'>Delete</button>
                </td>

            </tr>
        </tbody>
    </table>
</div>
