(function() {
  var runCallback = function(callback) {
    if (typeof callback === 'undefined') return;
    var args = [].splice.call(arguments, 1, arguments.length - 1);
    callback.apply(callback, args);
  }

  var extend = function(classToExtend) {
    var newClass = function(sandbox) {
      this.sandbox = sandbox;
      this.init();
    }

    newClass.prototype = Object.create(classToExtend.prototype);
    newClass.prototype.constructor = newClass;

    return newClass;
  }

  var selectors = {
    // Общее
    removeBtn:       '.js-bricks-remove-btn',
    rowPrototype:    '.js-bricks-row-proto',

    // Схема
    fieldsTable:     '.js-bricks-fields-table',
    addFieldBtn:     '.js-bricks-fields-add',

    // Список блоков
    addBrickBtn:     '.js-bricks-brick-add',
    schemaSelect:    '.js-bricks-schemas-select',
    groupSelect:     '.js-bricks-group-select',

    // Список групп
    groupsTable:     '.js-bricks-groups-table',
    addGroupForm:    '.js-bricks-group-add-form',
    groupsFormSave:  '.js-bricks-groups-form-save',
    groupsSaveBtn:   '.js-bricks-groups-save',

    // Дополнения модулей
    bricksSelect:    '.js-bricks-select',
    relationsTable:  '.js-bricks-relations-table',
  }


  var RowsHandler = function(params) {
    var _ = this;

    if (! (typeof params === 'object' && params.row && params.container)) return;

    var row = params.row.cloneNode(true);

    if (params.row.parentNode) {
      params.row.parentNode.removeChild(params.row);
    }

    _.rowPrototype = row;
    _.rowPrototype.removeAttribute('class');

    _.container = params.container;

    var funcs = ['beforeAdd', 'afterAdd', 'beforeRemove', 'afterRemove'];

    for (var i = 0; i < funcs.length; i++) {
      var funcName = funcs[i];

      if (funcName in params) {
        _[funcName] = params[funcName];
      }
      else {
        _[funcName] = _.defaultFunc;
      }
    }

    _.initRemoveBtns();
    _.data = {};
    _.ready = true;
  }

  RowsHandler.prototype.defaultFunc = function() {
    runCallback.apply(runCallback, arguments);
  }

  RowsHandler.prototype.add = function(data) {
    var _ = this;

    if (!_.ready) return;
    _.setData(data);

    _.beforeAdd(function(data){
      _.setData(data);
      _.makeNewRow();
    });
  }

  RowsHandler.prototype.setData = function(data) {
    if (! data) return;

    this.data = data;
  }


  RowsHandler.prototype.makeNewRow = function() {
    var _ = this;

    var row = _.rowPrototype.cloneNode(true);

    var rowHtml = row.innerHTML;

    for (var key in _.data) {
      rowHtml = rowHtml.replace(new RegExp('%' + key + '%', 'g'), _.data[key]);
    }

    row.innerHTML = rowHtml;

    var removeBtn = row.querySelector(selectors.removeBtn);

    if (removeBtn) {
      _.setRemoveEvent(removeBtn, row);
    }

    _.container.appendChild(row);

    _.afterAdd(row, _.data);
  }

  RowsHandler.prototype.setRemoveEvent = function(btn, row) {
    var _ = this;

    btn.addEventListener('click', function() {
      _.beforeRemove(function(){
        if (row && row.parentNode) {
          row.parentNode.removeChild(row);
        }

        _.afterRemove();
      });
    }, false);
  }

  RowsHandler.prototype.initRemoveBtns = function() {
    var _ = this;

    [].forEach.call(_.container.querySelectorAll(selectors.removeBtn), function(btn) {
      var row = $(btn).closest('tr').get(0);
      _.setRemoveEvent(btn, row);
    });
  }

  var BasePageController = extend(Object);
  BasePageController.prototype.init = function() {}

  var ListController = extend(BasePageController);

  ListController.prototype.init = function() {
    this.findCheckboxes();
  }

  ListController.prototype.findCheckboxes = function() {
    var _ = this;

    _.checkboxes = _.sandbox.contentContainer.querySelectorAll('.niceCheck input');
  }

  ListController.prototype.getIdsFromCheckboxes = function() {
    var _ = this;

    var ids = [];
    [].forEach.call(_.checkboxes, function(el){
      if (el.checked) {
        ids.push(el.value);
      }
    });

    return ids;
  }

  var SchemaController = extend(BasePageController);

  SchemaController.prototype.init = function() {
    var _ = this;

    var table        = _.sandbox.contentContainer.querySelector(selectors.fieldsTable);
    var tbody        = table.querySelector('tbody');
    var addFieldBtn  = table.querySelector(selectors.addFieldBtn);
    var rowPrototype = tbody.querySelector(selectors.rowPrototype);

    _.rowsHandler = new RowsHandler({
      row:       rowPrototype,
      container: tbody,
      afterAdd: function(row){
        initChosenSelect();
      },
    });

    addFieldBtn.addEventListener('click', function(){
      _.rowsHandler.add();
    }, false);
  }

  var SchemasController = extend(ListController);

  var BricksController = extend(ListController);

  BricksController.prototype.init = function() {
    var _ = this;
    _.findCheckboxes();

    var addBrickBtn  = document.querySelector(selectors.addBrickBtn);
    var schemaSelect = document.querySelector(selectors.schemaSelect);

    addBrickBtn.addEventListener('click', function() {
      var id = schemaSelect.options[schemaSelect.selectedIndex].value;
      _.sandbox.sendPjaxRequest(_.sandbox.getActionPath('create/' + id));
    }, false);

    _.initGroupSelect();
  }

  BricksController.prototype.initGroupSelect = function() {
    var _ = this;

    $(selectors.groupSelect).each(function(){
      var brickId = this.getAttribute('data-brick');
      if (! brickId) return;

      $(this).chosen().change(function(){
        _.sandbox.sendAjaxRequest(_.sandbox.getActionPath('setgroup'), {
          brick_id: brickId,
          group_id: $(this).val(),
        });
      });
    });
  }

  var RelationsController = extend(ListController);

  RelationsController.prototype.init = function() {
    var _ = this;
    _.findCheckboxes();

    var relationsTable = document.querySelector(selectors.relationsTable);
    var tbody          = relationsTable.querySelector('tbody');
    var addBrickBtn    = relationsTable.querySelector(selectors.addBrickBtn);
    var bricksSelect   = relationsTable.querySelector(selectors.bricksSelect);
    var rowPrototype   = tbody.querySelector(selectors.rowPrototype);

    _.rowsHandler = new RowsHandler({
      row:       rowPrototype,
      container: tbody,
      afterAdd: function(row, data){
        var groupSelect = row.querySelector(selectors.groupSelect);
        if (! groupSelect) return;

        [].forEach.call(groupSelect.options, function(option, index){
          if (option.value === data.group) {
            groupSelect.selectedIndex = index;
            return false;
          }
        });

        initChosenSelect();
      },
    });

    addBrickBtn.addEventListener('click', function(){
      var option = bricksSelect.options[bricksSelect.selectedIndex];

      var data = {
        id:    option.getAttribute('data-id'),
        name:  option.getAttribute('data-name'),
        title: option.innerText,
        group: option.getAttribute('data-group'),
      }

      _.rowsHandler.add(data);
    }, false);
  }

  var GroupsController = extend(ListController);

  GroupsController.prototype.init = function() {
    var _ = this;
    sortInit();
    _.findCheckboxes();

    var groupsTable     = document.querySelector(selectors.groupsTable);
    var tbody           = groupsTable.querySelector('tbody');
    var rowPrototype    = tbody.querySelector(selectors.rowPrototype);
    var addGroupForm    = document.querySelector(selectors.addGroupForm);
    _.groupsSaveBtn     = document.querySelector(selectors.groupsSaveBtn);

    _.xhr = false;

    _.initInputChangeEvent(tbody);

    _.rowsHandler = new RowsHandler({
      row:       rowPrototype,
      container: tbody,
      beforeAdd: function(callback){
        if (_.xhr !== false) {
          _.xhr.abort();
        }

        var data = {};
        $(addGroupForm).serializeArray().forEach(function(item){
          data[item.name] = item.value;
        });

        _.xhr = _.sandbox.sendAjaxRequest(_.sandbox.getActionPath('add'), data, function(response){
          _.xhr = false;
          if (response && response.status === 'success') {
            $(addGroupForm).trigger('reset');
            data.id = response.id;
            runCallback(callback, data);
          }
        });
      },
      afterAdd: function(row) {
        initNiceCheck();
        _.findCheckboxes();
        _.initInputChangeEvent(row);
      },
    });

    addGroupForm.addEventListener('submit', function(e) {
      e.preventDefault();
      _.rowsHandler.add();
    }, false);
  }

  GroupsController.prototype.initInputChangeEvent = function(container) {
    var _ = this;

    var inputs = container.querySelectorAll('input[type="text"]');
    [].forEach.call(inputs, function(el) {
      el.addEventListener('keydown', function() {
        _.groupsSaveBtn.classList.remove('hide');
      });
    });
  }

  var sandbox = function(){
    var _ = this;

    _.contentContainer = document.querySelector('#mainContent');

    if (! _.contentContainer) return;

    _.init();
    _.__pjaxRequest = false;

    $(document).on('pjax:end', function(){
      _.init();
    });
  }

  sandbox.prototype.basePath = '/admin/components/cp/bricks/';

  sandbox.prototype.controllersSegments = {
    brick:     'data',
    bricks:    'data',
    schema:    'schemas',
    schemas:   'schemas',
    groups:    'groups',
    relations: 'relations',
  };

  sandbox.prototype.getActionPath = function(action) {
    var _ = this;

    return _.basePath + _.controllersSegments[_.pageType] + '/' + action;
  }

  sandbox.prototype.init = function() {
    var _ = this;

    _.pageType = _.detectType();

    switch (_.pageType) {
      case 'schema':
        _.pageController = new SchemaController(_);
        break;

      case 'schemas':
        _.pageController = new SchemasController(_);
        break;

      case 'groups':
        _.pageController = new GroupsController(_);
        break;

      case 'bricks':
        _.pageController = new BricksController(_);
        break;

      case 'relations':
        _.pageController = new RelationsController(_);
        break;
    }
  }

  sandbox.prototype.detectType = function() {
    var _ = this;

    var input = _.contentContainer.querySelector('#js-page-type');

    return input ? input.value : false;
  }

  sandbox.prototype.delete = function(id) {
    this.sendDeleteRequest([id]);
  }

  sandbox.prototype.deleteMany = function() {
    var _ = this;

    if (_.pageController instanceof ListController) {
      _.sendDeleteRequest(_.pageController.getIdsFromCheckboxes());
    }
  }

  sandbox.prototype.sendDeleteRequest = function(ids) {
    if (! ids) return;
    var _ = this;

    this.sendPjaxRequest(_.getActionPath('remove'), {ids: ids});
  }

  sandbox.prototype.sendAjaxRequest = function(url, data, callback) {
    var _ = this;

    showLoading();

    return $.post(url, data, function(response){
      hideLoading();

      if (response && response.message){
        if (response.status === 'error') {
          showMessage(lang('error'), response.message, 'error');
        }
        else{
          showMessage(lang('success'), response.message, 'success');
        }
      }

      runCallback(callback, response);
    }, 'json');
  }

  sandbox.prototype.sendPjaxRequest = function(url, data) {
    var _ = this;

    if (_.__pjaxRequest !== false) {
      _.__pjaxRequest.abort();
    }

    _.__pjaxRequest = $.pjax({
      url:       url,
      type:      'POST',
      data:      data,
      push:      true,
      replace:   false,
      container: '#mainContent',
      timeout:   1000,
    }).done(function() {
      _.init();
    });
  }

  $(document).ready(function(){
    window.bricks = new sandbox();
  });
}());


