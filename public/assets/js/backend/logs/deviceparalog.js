define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'logs/deviceparalog/index',
                    del_url: 'logs/deviceparalog/del',
                    detail_url: 'logs/deviceparalog/detail',
                    table: 'device_para_log',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'), operate:false},
                        {field: 'mac', title: __('Mac'), operate:false},
                        {field: 'runtime', title: __('Runtime'),formatter: Table.api.formatter.datetime, operate:false}
                    ]
                ],
                commonSearch: false,
                showToggle: false,
                showColumns: false,
                showExport: false,
                
                onClickRow: function (value) {
                    var id = value.id;
                    var options = table.bootstrapTable('getOptions');
                    var url = options.extend.detail_url + '/id/' + id;
                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                    })
                    .done(function(data) {
                        console.log(data);
                        var before_html = data['before_info'];
                        var after_html = data['after_info'];
                        $(".before_info").html();
                        $(".before_info").html(before_html);
                        $(".after_info").html();
                        $(".after_info").html(after_html);
                    })
                    .fail(function() {
                        console.log("error");
                    })
                    .always(function() {
                        console.log("complete");
                    });
                }
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});