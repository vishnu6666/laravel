<form id="deleteForm" name="deleteForm" action="" method="post">
    @csrf
    @method('delete')
</form>

<form id="changeStatusForm" name="changeStatusForm" action="" method="post">
    @csrf
</form>

<form id="tableStatusChangeForm" name="tableStatusChangeForm" action="" method="post">
    @csrf
</form>

<form id="clearAllNotifcationForm" name="clearAllNotifcationForm" action="">
    @csrf
</form>

<form id="sendNotificationForm" name="sendNotificationForm" action="">
    @csrf
</form>
