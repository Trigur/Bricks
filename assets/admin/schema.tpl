<input type="hidden" value="schema" id="js-page-type">

<section class="mini-layout">
    <div class="frame_title clearfix">
        <div class="pull-left">
            <span class="help-inline"></span>
            <span class="title">{lang("Схемы блоков", 'bricks')}</span>
        </div>

        <div class="pull-right">
            <div class="d-i_b">
                <a href="/admin/components/cp/bricks/schemas" class="t-d_n m-r_15 pjax"><span class="f-s_14">←</span><span class="t-d_u">{lang('Вернуться', 'bricks')}</span></a>

                <button type="button" class="btn btn-small btn-primary formSubmit" data-form="#schema-form" data-submit data-action="toedit"><i class="icon-white icon-ok"></i>{lang("Сохранить", 'bricks')}</button>

                <button type="button" class="btn btn-small formSubmit" data-form="#schema-form" data-submit data-action="tomain"><i class="icon-check"></i>{lang("Сохранить и выйти", 'bricks')}</button>

                {if $type === 'edit'}
                    <button type="button" class="btn btn-small btn-danger" onclick="$('.schema_delete_dialog').modal();"><i class="icon-trash"></i>{lang("Удалить", 'bricks')}</button>
                {/if}
            </div>
        </div>
    </div>
</section>

<div class="row-fluid form-horizontal">
    <form method="post" enctype="multipart/form-data" id="schema-form">
        <table class="table table-bordered table-hover table-condensed content_big_td">
            <thead>
                <tr>
                    <th colspan="6">
                        {if $type === 'create'}
                            {lang("Создание", 'bricks')}
                        {else :}
                            {lang("Редактирование", 'bricks')}
                        {/if}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="6">
                        <div class="inside_padd">
                            <div class="row-fluid">

                                <div class="control-group">
                                    <label class="control-label" for="schema-name">{lang("Название", 'bricks')}:</label>
                                    <div class="controls">
                                        <input type="text" value="{$schema['name']}" name="name" id="schema-name" required maxlength="50">
                                        <div class="help-block">{lang("латиницей", 'bricks')}</div>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="schema-title">{lang("Заголовок", 'bricks')}:</label>
                                    <div class="controls">
                                        <input type="text" value="{$schema['title']}" name="title" id="schema-title" required maxlength="255">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <table class="bricks-fields-table table table-bordered table-hover table-condensed content_big_td js-bricks-fields-table">
            <thead>
                <tr>
                    <th>{lang('Название', 'bricks')}</th>
                    <th>{lang('Заголовок', 'bricks')}</th>
                    <th>{lang('Тип', 'bricks')}</th>
                    <th>
                        <a class="btn btn-small btn-success bricks-add-btn js-bricks-fields-add"><i class="icon-plus-sign icon-white" style="margin-right: 0;"></i></a>
                    </th>
                </tr>
            </thead>

            <tbody>
                <tr class="bricks-fields-table-proto js-bricks-row-proto">
                    <td>
                        <input type="text" name="fields[name][]" required maxlength="50">
                    </td>
                    <td>
                        <input type="text" name="fields[title][]" required maxlength="255">
                    </td>
                    <td>
                        <select name="fields[type][]">
                            {foreach $fieldsTypes as $value}
                                <option value="{$value}">{$value}</option>
                            {/foreach}
                        </select>
                    </td>
                    <td>
                        <a class="btn btn-small btn-danger js-bricks-remove-btn" required><i class="icon-trash"></i></a>
                    </td>
                </tr>

                {foreach $schema['fields'] as $field}
                    <tr>
                        <td>
                            <input type="text" name="fields[name][]" value="{$field['name']}" required maxlength="50">
                        </td>
                        <td>
                            <input type="text" name="fields[title][]" value="{$field['title']}" required maxlength="255">
                        </td>
                        <td>
                            <select name="fields[type][]">
                                {foreach $fieldsTypes as $value}
                                    <option value="{$value}"{if $value === $field['type']} selected{/if}>{$value}</option>
                                {/foreach}
                            </select>
                        </td>
                        <td>
                            <a class="btn btn-small btn-danger js-bricks-remove-btn" required><i class="icon-trash"></i></a>
                        </td>
                    </tr>
                {/foreach}
            </tbody>
        </table>

    </form>
</div>

{if $type === 'edit'}
<div class="modal hide fade schema_delete_dialog">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>{lang("Удалить схему?", 'bricks')}</h3>
    </div>

    <div class="modal-body">
        {lang("Удаление схемы повлечёт удаление всех дочерних блоков.", 'bricks')}
    </div>

    <div class="modal-footer">
        <a class="btn" onclick="$('.modal').modal('hide');">{lang("Отмена", 'bricks')}</a>
        <a class="btn btn-primary" onclick="bricks.delete({$schema['id']});$('.modal').modal('hide');">{lang("Удалить", 'bricks')}</a>
    </div>
</div>
{/if}