define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            Table.api.init({
                extend: {
                    index_url: 'contentset/columnset/index',
                    edit_url: function(){
                        return 'javascrpit:;';
                    },
                    multi_url: 'contentset/columnset/multi',
                    dragsort_url: '',
                    status_url: 'contentset/columnset/status',
                    table: 'column_custom',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                escape: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'ccid', visible: false},
                        {field: 'title', title: __('Title'), align: 'left', formatter: Controller.api.formatter.title},
                        {field: 'filepath', title: __('Preview'), operate:false, formatter: Controller.api.formatter.thumb},
                        {field: 'language_type', title: __('Language_type'), formatter: Controller.api.formatter.language_type},
                        {field: 'weigh', title: __('Weigh')},
                        {field: 'save_set', title: __('Save_set'), formatter: Controller.api.formatter.save_set_text},
                        {field: 'status', title: __('Status'), events: Controller.api.events.operate, formatter: Controller.api.formatter.status},
                        {field: 'audit_status', title: __('Audit_status'),
                            formatter: Controller.api.formatter.audit_status,
                            searchList: {"0":__('No audit'),"1":__('No egis'), "2":__('Egis'), "3":__('No publish'), "4":__('Publish')}
                        },
                        {field: 'rid', title:'<a href="javascript:;" class="btn btn-success btn-xs btn-toggle"><i class="fa fa-chevron-up"></i></a>',
                            operate: false, formatter: Controller.api.formatter.subnode
                        },
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange',
                            formatter: Table.api.formatter.datetime, operate:false},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange',
                            formatter: Table.api.formatter.datetime, operate:false},
                        {field: 'operate', title: __('Operate'), table: table,
                            buttons: [
                                {name: 'resources', text: __('resources'), icon: 'fa fa-list',
                                    classname: 'btn btn-xs btn-primary btn-resources', events: Controller.api.events.operate},
                            ],
                            events: Controller.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ],
                showToggle: false,
                showExport: false,
                pagination: false,
                search: false,
                commonSearch: false,
            });

            // 为表格绑定事件
            Table.api.bindevent(table);

            //Bootstrap-table配置
            var options = table.bootstrapTable('getOptions');

            //当内容渲染完成后
            table.on('post-body.bs.table', function (e, settings, json, xhr) {
                //默认隐藏非一级栏目节点
                $(".btn-node-sub:not('.level_first')").closest("tr").hide();

                //显示隐藏子节点
                $(".btn-node-sub").off("click").on("click", function (e) {
                    var status = $(this).data("shown") ? true : false;
                    $("a.btn[data-pid='" + $(this).data("id") + "']").each(function () {
                        $(this).closest("tr").toggle(!status);
                    });
                    $(this).data("shown", !status);
                    return false;
                });

                //客户选择
                $(".custom_select").off("change").on("change", function(){
                    var $custom_id = $(".custom_select").val();
                    options.queryParams = function (params) {
                        return{
                            filter: JSON.stringify({custom_id: $custom_id}),
                            op: JSON.stringify({category_id: '='}),
                        };
                    };
                    table.bootstrapTable('refresh', {});
                    return false;
                });
            });

            //展开隐藏一级
            $(document.body).on("click", ".btn-toggle", function (e) {
                $("a.btn[data-id][data-pid][data-pid!=0].disabled").closest("tr").hide();
                var that = this;
                var show = $("i", that).hasClass("fa-chevron-down");
                $("i", that).toggleClass("fa-chevron-down", !show);
                $("i", that).toggleClass("fa-chevron-up", show);
                $("a.btn[data-id][data-pid][data-pid!=0]").not('.disabled').closest("tr").toggle(show);
                $(".btn-node-sub[data-pid=0]").data("shown", show);
            });
            //展开隐藏全部
            $(document.body).on("click", ".btn-toggle-all", function (e) {
                var that = this;
                var show = $("i", that).hasClass("fa-plus");
                $("i", that).toggleClass("fa-plus", !show);
                $("i", that).toggleClass("fa-minus", show);
                $(".btn-node-sub.disabled").closest("tr").toggle(show);
                $(".btn-node-sub").data("shown", show);
            });
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        resources: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    resource_status_url: 'contentset/columnset/resource_status',
                    dragsort_url: 'contentset/columnset/resource_dragsort',
                    status_url: 'contentset/columnset/status',
                }
            });
            var resource_table = $("#resource_table");

            // 初始化表格
            resource_table.bootstrapTable({
                url: 'contentset/columnset/resources',
                sortName: 'id',
                search: false,
                queryParams: function (params) {
                    params.filter = JSON.stringify({'ccid': Config.ccid, 'id':Config.id});
                    return {
                      filter: params.filter
                    };
                },
                columns: [
                    [
                        {checkbox: true},
                        //{field: 'id', title: __('ID')},
                        {field: 'title', title: __('Title')},
                        {field: 'resource', title: __('Preview'), operate:false, formatter: Controller.api.formatter.thumb},
                        {field: 'resource', title: __('Resources'), operate:false,formatter: Controller.api.formatter.url},
                        {field: 'describe', title: __('Describe')},
                        {field: 'weigh', title:__('Weigh')},
                        {field: 'size', title: __('Size')+'(MB)', operate:false},
                        {field: 'status', title: __('Status'), events: Controller.api.events.operate,
                            formatter: Controller.api.formatter.resource_status},
                        {field: 'operate', title: __('Operate'), table: resource_table, formatter: Controller.api.formatter.resource_operate}
                    ]
                ],
                search:false,
                showToggle: false,
                commonSearch: false,
                showExport: false,
                showColumns: false,
            });

            // 为表格绑定事件
            Table.api.bindevent(resource_table);

            // 拖拽排序
            require(['dragsort'], function () {
                //绑定拖动排序
                $("tbody", resource_table).dragsort({
                    itemSelector: 'tr:visible',
                    dragSelector: "a.btn-resource-dragsort",
                    dragEnd: function (a, b) {
                        var element = $("a.btn-resource-dragsort", this);
                        var data = resource_table.bootstrapTable('getData');
                        var current = data[parseInt($(this).data("index"))];

                        // 获取排序ID
                        var ids = $.map($("tbody tr:visible", resource_table), function (tr) {
                            return data[parseInt($(tr).data("index"))]['id'];
                        });
                        // 获取CCID
                        var ccid = current['ccid'];

                        var params = {
                            url: resource_table.bootstrapTable('getOptions').extend.dragsort_url,
                            data: {
                                ids: ids.join(','),
                                ccid: ccid
                            }
                        };
                        Fast.api.ajax(params, function (data, ret) {
                            var success = $(element).data("success") || $.noop;
                            if (typeof success === 'function') {
                                if (false === success.call(element, data)) {
                                    return false;
                                }
                            }
                            resource_table.bootstrapTable('refresh');
                        }, function () {
                            var error = $(element).data("error") || $.noop;
                            if (typeof error === 'function') {
                                if (false === error.call(element, data)) {
                                    return false;
                                }
                            }
                            resource_table.bootstrapTable('refresh');
                        });
                    },
                    placeHolderTemplate: ""
                });
            });
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            },
            formatter: {
                title: function (value, row) {
                    return row.level == 1 ? "<span class='text-muted'>" + value + "</span>" : value;
                },
                thumb: function (value, row) {
                    return '<a href="' + row.fullurl + '" target="_blank"><img src="' + row.fullurl + '" alt="" style="max-height:90px;max-width:120px"></a>';
                },
                subnode: function (value, row) {
                    return '<a href="javascript:;" data-id="' + row.id + '" data-pid="' + row.pid + '" class="btn btn-xs '
                            + (row.level==1 ? 'level_first ' : ' ')
                        + (row.haschild == 1 ? 'btn-success' : 'btn-default disabled') + ' btn-node-sub"><i class="fa fa-sitemap"></i></a>';
                },
                language_type: function (value) {
                    return '<span class="text-success"><i class="fa fa-circle"></i> ' + __(value) + '</span>';
                },
                status: function (value, row, index) {
                    return "<a href='javascript:;' class='btn btn-" + (value=='hidden' ? "default" : "info") +
                        " btn-xs btn-status' >" + (value=='hidden'? __('Hidden') : __('Normal')) + "</a>";
                },
                audit_status: function (value) {
                    var text = '';
                    switch (value) {
                        case 0:
                            text = 'No audit';
                            break;
                        case 1:
                            text = 'No egis';
                            break;
                        case 2:
                            text = 'Egis';
                            break;
                        case 3:
                            text = 'No publish';
                            break;
                        case 4:
                            text = 'Publish';
                            break;
                        default:
                            text = 'Undefined state';
                    }

                    return '<span class="text-info"><i class="fa fa-circle"></i> ' + __(text) + '</span>';
                },
                save_set_text: function (value, row) {
                    var text = '';
                    switch (value) {
                        case 0:
                            text = 'Save_set_text0';
                            break;
                        case 1:
                            text = 'Save_set_text1';
                            break;
                        case 2:
                            text = 'Save_set_text2';
                            break;
                    }
                    return '<span class="text-info">' + __(text) + '</span>';
                },
                thumb: function (value, row) {
                    if(row.resource_type == 'video'){
                        return '<a href="' + row.fullurl + '" target="_blank"><video src="' + row.fullurl + '"  style="max-height:90px;max-width:120px"></a>';
                    }else if(row.resource_type == 'image'){
                        return '<a href="' + row.fullurl + '" target="_blank"><img src="' + row.fullurl + '"  style="max-height:90px;max-width:120px"></a>';
                    }else if(row.resource_type == 'url'){
                        return '-';
                    }
                },
                url: function (value, row) {
                    return '<a href="' + row.fullurl + '" target="_blank" class="label bg-green">' + value + '</a>';
                },
                resource_status: function (value, row, index) {
                    return "<a href='javascript:;' class='btn btn-" + (value=='hidden' ? "default" : "info") +
                        " btn-xs btn-resource-status' >" + (value=='hidden'? __('Hidden') : __('Normal')) + "</a>";
                },
                resource_operate: function (value, row, index) {
                    var table = this.table;
                    // 操作配置
                    var options = table ? table.bootstrapTable('getOptions') : {};
                    // 默认按钮组
                    var buttons = $.extend([], this.buttons || []);

                    if (options.extend.dragsort_url !== '') {
                        buttons.push({
                            name: 'dragsort',
                            icon: 'fa fa-arrows',
                            title: __('Drag to sort'),
                            classname: 'btn btn-xs btn-primary btn-resource-dragsort'
                        });
                    }
                    return Table.api.buttonlink(this, buttons, value, row, index, 'operate');
                },
            },
            events: {
                operate: {
                    // 修改栏目配置
                    'click .btn-editone': function (e, value, row, index) {
                        e.stopPropagation();
                        e.preventDefault();
                        var table = $(this).closest('table');
                        var url = 'contentset/columnset/edit/ccid/'+row['ccid']+'/rid/'+row['rid'];
                        Fast.api.open(Table.api.replaceurl(url, row, table), __('Edit'), $(this).data() || {});
                    },
                    // 动态修改栏目状态
                    'click .btn-status': function (e, value, row, index){
                        element = this;
                        e.preventDefault();
                        var table = $(this).closest('table');
                        var options = table.bootstrapTable('getOptions');
                        var url = options.extend.status_url;
                        var params = {'ccid':row['ccid'], 'id':row['id'], 'status':value=='normal'?'hidden':'normal'};
                        var options = {url: url, data: {params:params}};
                        Fast.api.ajax(options, function (data, ret) {
                            var success = $(element).data("success") || $.noop;
                            if (typeof success === 'function') {
                                if (false === success.call(element, data, ret)) {
                                    return false;
                                }
                            }
                            table.bootstrapTable('refresh');
                        }, function (data, ret) {
                            var error = $(element).data("error") || $.noop;
                            if (typeof error === 'function') {
                                if (false === error.call(element, data, ret)) {
                                    return false;
                                }
                            }
                        });
                    },
                    // 资源列表
                    'click .btn-resources': function (e, value, row, index) {
                        e.stopPropagation();
                        e.preventDefault();
                        if(row['level'] != 3){
                            Toastr.error(__('Resources tips'));
                            return;
                        }
                        var table = $(this).closest('table');
                        var url = 'contentset/columnset/resources/ccid/'+row['ccid']+'/id/'+row['id'];
                        Fast.api.open(Table.api.replaceurl(url, row, table), __('Resources'), $(this).data() || {});
                    },
                    // 动态修改资源状态
                    'click .btn-resource-status': function (e, value, row, index){
                        element = this;
                        e.preventDefault();
                        var table = $(this).closest('table');
                        var options = table.bootstrapTable('getOptions');
                        var url = options.extend.resource_status_url;
                        var params = {'ccid':row['ccid'], 'id':row['id'], 'status':value=='normal'?'hidden':'normal'};
                        var options = {url: url, data: {params:params}};
                        Fast.api.ajax(options, function (data, ret) {
                            var success = $(element).data("success") || $.noop;
                            if (typeof success === 'function') {
                                if (false === success.call(element, data, ret)) {
                                    return false;
                                }
                            }
                            table.bootstrapTable('refresh');
                        }, function (data, ret) {
                            var error = $(element).data("error") || $.noop;
                            if (typeof error === 'function') {
                                if (false === error.call(element, data, ret)) {
                                    return false;
                                }
                            }
                        });
                    },
                }
            }
        }
    };
    return Controller;
});