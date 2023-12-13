<!-- BEGIN: main -->
<form action="" method="post" class="form-horizontal">
    <div class="panel panel-default">
        <div class="panel-body">
			 <div class="form-group">
                <label class="col-sm-4 control-label">{LANG.config_groups_director}</label>
                <div class="col-sm-20" style="height: 200px; padding: 10px; border: solid 1px #ddd; overflow: scroll;">
                    <!-- BEGIN: groupsd -->
                    <label class="show"><input type="checkbox" name="groups_director[]" value="{GROUPS.value}" {GROUPS.checked} />{GROUPS.title}</label>
                    <!-- END: groupsd -->
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">{LANG.config_groups_manage}</label>
                <div class="col-sm-20" style="height: 200px; padding: 10px; border: solid 1px #ddd; overflow: scroll;">
                    <!-- BEGIN: groups -->
                    <label class="show"><input type="checkbox" name="groups_manage[]" value="{GROUPS.value}" {GROUPS.checked} />{GROUPS.title}</label>
                    <!-- END: groups -->
                </div>
            </div>
			 <div class="form-group">
                <label class="col-sm-4 control-label">{LANG.config_groups_account}</label>
                <div class="col-sm-20" style="height: 200px; padding: 10px; border: solid 1px #ddd; overflow: scroll;">
                    <!-- BEGIN: groupsa -->
                    <label class="show"><input type="checkbox" name="groups_account[]" value="{GROUPS.value}" {GROUPS.checked} />{GROUPS.title}</label>
                    <!-- END: groupsa -->
                </div>
            </div>
			 <div class="form-group">
                <label class="col-sm-4 control-label">{LANG.config_groups_hr_department}</label>
                <div class="col-sm-20" style="height: 200px; padding: 10px; border: solid 1px #ddd; overflow: scroll;">
                    <!-- BEGIN: groupsh -->
                    <label class="show"><input type="checkbox" name="groups_hr_department[]" value="{GROUPS.value}" {GROUPS.checked} />{GROUPS.title}</label>
                    <!-- END: groupsh -->
                </div>
            </div>
			 <div class="form-group">
                <label class="col-sm-4 control-label">{LANG.config_groups_sales}</label>
                <div class="col-sm-20" style="height: 200px; padding: 10px; border: solid 1px #ddd; overflow: scroll;">
                    <!-- BEGIN: groupss -->
                    <label class="show"><input type="checkbox" name="groups_sales[]" value="{GROUPS.value}" {GROUPS.checked} />{GROUPS.title}</label>
                    <!-- END: groupss -->
                </div>
            </div>
			 <div class="form-group">
                <label class="col-sm-4 control-label">{LANG.config_groups_maketting}</label>
                <div class="col-sm-20" style="height: 200px; padding: 10px; border: solid 1px #ddd; overflow: scroll;">
                    <!-- BEGIN: groupsm -->
                    <label class="show"><input type="checkbox" name="groups_maketting[]" value="{GROUPS.value}" {GROUPS.checked} />{GROUPS.title}</label>
                    <!-- END: groupsm -->
                </div>
            </div>
			<div class="form-group">
                <label class="col-sm-4 control-label">{LANG.config_groups_design}</label>
                <div class="col-sm-20" style="height: 200px; padding: 10px; border: solid 1px #ddd; overflow: scroll;">
                    <!-- BEGIN: groupsds -->
                    <label class="show"><input type="checkbox" name="groups_design[]" value="{GROUPS.value}" {GROUPS.checked} />{GROUPS.title}</label>
                    <!-- END: groupsds -->
                </div>
            </div>
			<div class="form-group">
                <label class="col-sm-4 control-label">{LANG.config_groups_technology}</label>
                <div class="col-sm-20" style="height: 200px; padding: 10px; border: solid 1px #ddd; overflow: scroll;">
                    <!-- BEGIN: groupst -->
                    <label class="show"><input type="checkbox" name="groups_technology[]" value="{GROUPS.value}" {GROUPS.checked} />{GROUPS.title}</label>
                    <!-- END: groupst -->
                </div>
            </div>
			<div class="form-group">
                <label class="col-sm-4 control-label">{LANG.config_groups_storehouse}</label>
                <div class="col-sm-20" style="height: 200px; padding: 10px; border: solid 1px #ddd; overflow: scroll;">
                    <!-- BEGIN: groupssh -->
                    <label class="show"><input type="checkbox" name="groups_storehouse[]" value="{GROUPS.value}" {GROUPS.checked} />{GROUPS.title}</label>
                    <!-- END: groupssh -->
                </div>
            </div>
			<div class="form-group">
                <label class="col-sm-4 control-label">{LANG.config_groups_customer}</label>
                <div class="col-sm-20" style="height: 200px; padding: 10px; border: solid 1px #ddd; overflow: scroll;">
                    <!-- BEGIN: groupsc -->
                    <label class="show"><input type="checkbox" name="groups_customer[]" value="{GROUPS.value}" {GROUPS.checked} />{GROUPS.title}</label>
                    <!-- END: groupsc -->
                </div>
            </div>
			<div class="form-group">
                <label class="col-sm-4 control-label">{LANG.config_groups_collaborators}</label>
                <div class="col-sm-20" style="height: 200px; padding: 10px; border: solid 1px #ddd; overflow: scroll;">
                    <!-- BEGIN: groupsclb -->
                    <label class="show"><input type="checkbox" name="groups_collaborators[]" value="{GROUPS.value}" {GROUPS.checked} />{GROUPS.title}</label>
                    <!-- END: groupsclb -->
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 text-right">{LANG.config_default_status}</label>
                <div class="col-sm-20">
                    <!-- BEGIN: status -->
                    <label><input type="checkbox" name="default_status[]" value="{STATUS.index}"{STATUS.checked}>{STATUS.value}</label>&nbsp;&nbsp;&nbsp;&nbsp;
                    <!-- END: status -->
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 text-right">Cấu hình trạng thái dự án</label>
                <div class="col-sm-20">
                    <div id="array_status">
                        <!-- BEGIN: status_cfg --><input class="form-control" type="text" name="array_status[id][]" value="{STATUS_CFG.id}" style="width:60px"/> <input class="form-control" type="text" name="array_status[txt][]" value="{STATUS_CFG.txt}"/>&nbsp;<!-- END: status_cfg -->
                    </div>
                    <div class="btn btn-primary pointer" onclick="addRow()">Thêm</div>
                </div>
            </div>
			 <div class="form-group">
                <label class="col-sm-4 text-right">Cấu hình trạng thái thực hiện dự án</label>
                <div class="col-sm-20">
                    <div id="array_project_status">
                        <!-- BEGIN: project_status_cfg -->
							<input class="form-control" type="text" name="array_project_status[id][]" value="{PROJECTSTATUS_CFG.id}" style="width:60px"/>
							<input class="form-control" type="text" name="array_project_status[txt][]" value="{PROJECTSTATUS_CFG.txt}"/>&nbsp;
						<!-- END: project_status_cfg -->
                    </div>
                    <div class="btn btn-primary pointer" onclick="addProjectStatus()">Thêm</div>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center">
        <input type="submit" class="btn btn-primary" value="{LANG.save}" name="savesetting" />
    </div>
</form>
<script type="text/javascript">
    function addRow() {
        $('#array_status').append('<input class="form-control" type="text" name="array_status[id][]" value="" style="width:60px"/> <input class="form-control" type="text" name="array_status[txt][]" value=""/>&nbsp;');
    }
	function addProjectStatus() {
        $('#array_project_status').append('<input class="form-control" type="text" name="array_project_status[id][]" value="" style="width:60px"/> <input class="form-control" type="text" name="array_project_status[txt][]" value=""/>&nbsp;');
    }
</script>
<!-- END: main -->