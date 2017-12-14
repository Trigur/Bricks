<input type="hidden" value="groups" id="js-page-type">

<section class="mini-layout">
    <div class="frame_title clearfix">
        <div class="pull-left">
            <span class="help-inline"></span>
            <span class="title">{lang("Группы блоков", 'bricks')}</span>
        </div>

        <div class="pull-right">
            <div class="d-i_b">
                <button class="btn btn-small btn-danger disabled action_on" id="del_in_search" onclick="$('.groups_delete_dialog').modal();" disabled="disabled"><i class="icon-trash"></i>{lang("Удалить", 'bricks')}</button>

                <button type="submit" class="btn btn-small btn-success bricks-add-btn hide js-bricks-groups-save formSubmit" data-form="#groups-form" data-submit data-action="tomain"><i class="icon-white icon-ok"></i>{lang("Сохранить", 'bricks')}</button>

                <a href="/admin/components/init_window/bricks/data" class="btn btn-small pjax">{lang("Блоки", 'bricks')}</a>
            </div>
        </div>
    </div>

    <div class="row-fluid form-horizontal">
        <table class="table table-bordered table-hover table-condensed content_big_td">
            <tbody>
                <tr>
                    <td colspan="6">
                        <div class="inside_padd">
                            <div class="row-fluid">

                                <label class="control-label" for="group-title">{lang('Название', 'bricks')}:</label>
                                <div class="controls">
                                    <form class="bricks-group-add-form js-bricks-group-add-form">
                                        <table>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <input type="text" value="" name="name" id="group-name" required maxlength="50">
                                                    </td>
                                                    <td>
                                                        <input type="text" value="" name="title" id="group-title" required maxlength="255">
                                                    </td>
                                                    <td>
                                                        <button type="submit" class="btn btn-small btn-success bricks-add-btn"><i class="icon-white icon-plus-sign"></i>{lang("Добавить", 'bricks')}</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <form method="post" enctype="multipart/form-data" id="groups-form">
        <table class="table table-bordered table-hover table-condensed t-l_a js-bricks-groups-table">
            <thead>
                <th class="t-a_c span1">
                    <span class="frame_label">
                        <span class="niceCheck">
                            <input type="checkbox">
                        </span>
                    </span>
                </th>
                <th class="span3">
                    {lang("Название", 'bricks')}
                </th>
                <th>
                    {lang("Заголовок", 'bricks')}
                </th>
            </thead>

            <tbody class="sortable save_positions" data-url="/admin/components/init_window/bricks/groups/savepositions">
                <tr class="bricks-fields-table-proto js-bricks-row-proto">
                    <td class="t-a_c">
                        <input type="hidden" name="groups[id][]" value="%id%">
                        <span class="frame_label">
                            <span class="niceCheck">
                                <input type="checkbox" name="ids" value="%id%">
                            </span>
                        </span>
                    </td>
                    <td>
                        <input type="text" name="groups[name][]" value="%name%" required maxlength="50">
                    </td>
                    <td>
                        <input type="text" name="groups[title][]" value="%title%" required maxlength="255">
                    </td>
                </tr>
                {foreach $groups as $group}
                    <tr>
                        <td class="t-a_c">
                            <input type="hidden" name="groups[id][]" value="{$group['id']}">
                            <span class="frame_label">
                                <span class="niceCheck">
                                    <input type="checkbox" name="ids" value="{$group['id']}">
                                </span>
                            </span>
                        </td>
                        <td>
                            <input type="text" name="groups[name][]" value="{$group['name']}" required maxlength="50">
                        </td>
                        <td>
                            <input type="text" name="groups[title][]" value="{$group['title']}" required maxlength="255">
                        </td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
    </form>
</section>

<div class="modal hide fade groups_delete_dialog">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>{lang("Удалить группу(-ы)?", 'bricks')}</h3>
    </div>

    <div class="modal-body">
        {lang("Удаление группы не приведет к удалению блоков.", 'bricks')}
    </div>

    <div class="modal-footer">
        <a class="btn" onclick="$('.modal').modal('hide');">{lang("Отмена", 'bricks')}</a>
        <a class="btn btn-primary" onclick="bricks.deleteMany();$('.modal').modal('hide');">{lang("Удалить", 'bricks')}</a>
    </div>
</div>