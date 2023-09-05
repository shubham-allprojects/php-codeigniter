var _data;
var _current;
var _seq;
var _circiut_type;

function contents_height() {
  var h =
    $(document).height() - $("#gnb").outerHeight() - $("#bottom").outerHeight();
  $("#lnb").height(h + "px");
}

$(document).ready(function () {
  contents_height();
  window.setTimeout(contents_height, 300);
  $(window).resize(function () {
    $("#lnb").height("");
    window.setTimeout(contents_height, 300);
  });
  $("body").click(function () {
    if (typeof top.auto_disconnect == "function") {
      top.auto_disconnect();
    }
  });
});

function addCommas(nStr) {
  nStr += "";
  x = nStr.split(".");
  x1 = x[0];
  x2 = x.length > 1 ? "." + x[1] : "";
  var rgx = /(\d+)(\d{3})/;
  while (rgx.test(x1)) {
    x1 = x1.replace(rgx, "$1" + "," + "$2");
  }
  return x1 + x2;
}
/******************************************************************************
Ajax, Form, Link 관련 함수
******************************************************************************/

// Form을 Ajax로 호출 전체 설정 (target이 있는것 제외)
$(document).ready(function () {
  $("form").each(function () {
    if ($(this).attr("target") == "") {
      $(this).submit(function () {
        submit_form("#" + $(this).attr("id"));
        return false;
      });
    }
  });
});

// Ajax 호출시 기본 설정
/*
$.ajaxSetup({
    beforeSend  : function() { showLoader(); },
    complete    : function() { hideLoader(); },
    error       : function() { alert("The connection to the server has a problem. Please try again later."); }
});
*/

// Form을 Ajax로 호출
function submit_form(form) {
  var form = $(form);
  $.ajax({
    type: form.attr("method"),
    url: form.attr("action"),
    data: form.serialize(),
    success: function (response) {
      try {
        eval(response);
      } catch (e) {
        alert(
          "The connection to the server has a problem. Please try again later."
        );
        hideLoader();
      }
    },
    beforeSend: function () {
      showLoader();
    },
    complete: function () {
      hideLoader();
    },
    //error       : function() { alert("The connection to the server has a problem. Please try again later."); }
  });
}

// url을 Ajax로 호출
function submit_link(url) {
  $.ajax({
    type: "GET",
    url: url,
    success: function (response) {
      try {
        eval(response);
      } catch (e) {
        alert(
          "The connection to the server has a problem. Please try again later."
        );
        hideLoader();
      }
    },
    beforeSend: function () {
      showLoader();
    },
    complete: function () {
      hideLoader();
    },
    //error       : function() { alert("The connection to the server has a problem. Please try again later."); }
  });
}

// 삭제 경고후 Form Submit
function remove_form(form) {
  if (confirm("Do you want to delete?")) $(form).submit();
}

// 삭제 경고후 url 호출
function remove_link(url) {
  if (confirm("Do you want to delete?")) submit_link(url);
}

// 현재 페이지를 url로 전환
function href(url) {
  window.location.href = url;
}

/******************************************************************************
list, view, new, edit 관련 공통 함수
******************************************************************************/

// json error message 확인
function check_error(data) {
  if (typeof data != "undefined") {
    if (
      typeof data.errors != "undefined" &&
      $.isArray(data.errors) &&
      data.errors.length > 0
    ) {
      alert(data.errors[0]);
      return false;
    }
  }

  return true;
}

// list data 호출
function load_list(route = "", page, field, word, view) {
  if (typeof page == "undefined") page = "";
  if (typeof field == "undefined") field = "";
  if (typeof word == "undefined") word = "";
  if (typeof view == "undefined") view = "";

  $.getJSON(
    route + "/?p=" + page + "&f=" + field + "&w=" + word + "&v=" + view,
    function (data) {
      _data = data;
      close_all();
      if (check_error(data)) {
        create_list();
      }
      if (typeof _data.view != "undefined" && _data.view != "") {
        open_view(find_seq(_data.view));
      }
    }
  );
}

// list data 호출
function update_list(view, func) {
  if (typeof view == "undefined") view = "";

  if ($("#form_search_value").val() == 1) {
    var add_filter = view;
  } else {
    var add_filter = "";
  }

  $.getJSON(
    "/?c=" +
      _class +
      "&m=select&p=" +
      _data.page +
      "&f=" +
      _data.field +
      "&w=" +
      _data.word +
      "&v=" +
      view +
      "&filter_no=" +
      add_filter,
    function (data) {
      _data = data;
      close_all();
      if (check_error(data)) {
        create_list();
      }
      if (typeof _data.view != "undefined" && _data.view != "") {
        open_view(find_seq(_data.view));

        if ($.isFunction(func)) func();
      }
    }
  );
}

// searh list data 호출
function load_list_search(route = "") {
  $.getJSON(
    route +
      "/?f=" +
      $("#form_search select[name='field']").val() +
      "&w=" +
      $("#form_search input[name='word']").val(),
    function (data) {
      _data = data;
      close_all();
      if (check_error(data)) {
        create_list();
      }
    }
  );
}

// 리스트 생성 (각 페이지에서 재정의)
function create_list() {}

// 페이지 네비게이션 생성
function create_pagination() {
  $("#pagination").html("");

  arr_page = new Array();
  arr_page.push("[");

  // prev 임시 처리

  $.each(_data.pages, function (name, value) {
    if (name == "prev")
      arr_page.push(
        '<a href="javascript:void(0)" onclick="load_list(' +
          value +
          ", '" +
          _data.field +
          "', '" +
          _data.word +
          '\')" style="text-decoration:none">' +
          " " +
          '<img src="/img/menu/button_grey_list_prev.png">' +
          " " +
          "</a>"
      );
  });

  $.each(_data.pages, function (name, value) {
    if (name != "prev" && name != "next") {
      if (_data.page == value || (_data.page == 0 && value == 1)) {
        arr_page.push(
          '<a href="javascript:void(0)" onclick="load_list(' +
            value +
            ", '" +
            _data.field +
            "', '" +
            _data.word +
            '\')" style="text-decoration: none; color:#c4c4c4">' +
            name +
            "</a>"
        );
      } else {
        arr_page.push(
          '<a href="javascript:void(0)" onclick="load_list(' +
            value +
            ", '" +
            _data.field +
            "', '" +
            _data.word +
            "')\">" +
            name +
            "</a>"
        );
      }
    }
  });

  $.each(_data.pages, function (name, value) {
    if (name == "next")
      arr_page.push(
        '<a href="javascript:void(0)" onclick="load_list(' +
          value +
          ", '" +
          _data.field +
          "', '" +
          _data.word +
          '\')"  style="text-decoration: none">' +
          " " +
          '<img src="/img/menu/button_grey_list_next.png">' +
          " " +
          "</a>"
      );
  });

  arr_page.push("]");
  $("#pagination").html(arr_page.join("&nbsp;"));
}

// List 라인 선택
function reset_list() {
  $("#list_body tr").each(function () {
    $(this).attr("class", "ov");
  });
  $("#list_" + _seq).attr("class", "on");
}

// _current data 동기화/반영
function sync_current() {
  for (var i = 0; i < _data.list.length; i++) {
    if (_data.list[i].No == _current.No) {
      _data.list[i] = _current;
      break;
    }
  }
}

// current data 셋팅
function set_current(seq) {
  if (typeof seq == "undefined") {
    _seq = "";
    _current = {};
    reset_list();
  } else {
    _seq = seq;
    if (_data) {
      _current = _data.list[_seq];
    }

    reset_list();
  }
}

// No로 seq 찾기
function find_seq(no) {
  for (var i = 0; i < _data.list.length; i++) {
    if (_data.list[i].No == no) {
      return i;
      break;
    }
  }
}

// view 호출
function open_view(seq) {
  set_current(seq);
  set_view();

  close_all();
  reset_list();
  $("#view_section").show();
}

// view 닫기
function close_view() {
  set_current();
  close_all();
}

// new 호출
function open_new() {
  set_current();
  set_new();

  close_all();
  $("#new_section").show();
}

// edit 호출
function open_edit(seq) {
  set_current(seq);
  set_edit();

  close_all();
  $("#edit_section").show();
}

// edit 닫기
function close_edit() {
  open_view(_seq);
}

// edit 닫기
function close_new() {
  set_current();
  close_all();
}

// delete 호출
function delete_no(no) {
  url = "/?c=" + _class + "&m=delete&no=" + no;
  remove_link(url);
}

// list 만 남기고 전부 감추기 (list 섹션 호출)
function close_all() {
  $("#new_section").hide();
  $("#edit_section").hide();
  $("#view_section").hide();
  $("#decoder_section").hide();
}

// view data 채우기
function set_view() {
  if (_current.disable == "1") {
    $.each(_current, function (name, value) {
      $("#view_" + name).html("-");
    });
    $("#view_disable_str").html(_current.disable_str);
  } else {
    $.each(_current, function (name, value) {
      //alert(name+":"+value)
      $("#view_" + name).html(value);
    });
  }
}

// edit data 채우기
function set_edit() {
  $.each(_current, function (name, value) {
    var element = $("#form_edit input[name='" + name + "']");
    if (element.attr("type") == "checkbox") {
      if (value == "1") element.attr("checked", true);
      else element.attr("checked", false);
    } else if (element.attr("type") == "radio") {
      $.each(element, function () {
        if (value == $(this).val()) $(this).attr("checked", true);
      });
    } else {
      element.val(value);
    }

    if (element.attr("type") == "file") {
      element.val("");
    }

    if (name == "file") {
      var upload_element = $("#form_edit input[name='upload_file']");
      if (upload_element.attr("type") == "file") {
        upload_element.val("");
      }
    }

    if ($("#form_edit select[name='" + name + "']").attr("multiple") == true)
      $("#form_edit select[name='" + name + "']").val(eval(value));
    else $("#form_edit select[name='" + name + "']").val(value);
  });
}

// new data 채우기
function set_new() {
  $("#form_new input").each(function () {
    if ($(this).attr("type") == "hidden") {
      if (
        $(this).attr("name") !== "UserNo" &&
        $(this).attr("name") !== "wizard"
      ) {
        $(this).val("");
      }
    } else if ($(this).attr("type") == "checkbox") {
      $(this).attr("checked", false);
    } else if ($(this).attr("type") == "text") {
      $(this).val("");
    } else if ($(this).attr("type") == "password") {
      $(this).val("");
    } else if ($(this).attr("type") == "file") {
      $(this).val("");
    }
  });

  $("#form_new select").each(function () {
    if ($(this).attr("multiple") == true) $(this).find("option").remove();
    else $(this).val("");
  });
}

// disable 옵션 적용
function disable_form() {
  var flag = $("#form_edit input[name='disable']").attr("checked");

  $("#form_edit input[type!='hidden']").each(function () {
    if ($(this).attr("name") != "disable") $(this).attr("disabled", flag);
  });

  $("#form_edit select").each(function () {
    $(this).attr("disabled", flag);
  });

  $("#form_edit button").each(function () {
    $(this).attr("disabled", flag);
  });
}

// disable 옵션 off
function disable_off() {
  var flag = $("#form_edit input[name='disable']").attr("checked");

  if (flag != true) {
    $("#form_edit input[type!='hidden']").each(function () {
      if ($(this).attr("name") != "disable") $(this).attr("disabled", flag);
    });

    $("#form_edit select").each(function () {
      $(this).attr("disabled", flag);
    });

    $("#form_edit button").each(function () {
      $(this).attr("disabled", flag);
    });
  }
}

// disable 옵션 저장
/*
function save_disable()
{
    $.ajax({
        type        : "POST",
        url         : "/?c="+ _class +"&m=disable",
        data        : $("#form_edit").serialize(),
        success     : function(response) {
            disable_form();
            _current.disable = $("#form_edit input[name='disable']").attr("checked") ? 1 : 0;
            sync_current();
        }
    });
}
*/

// circiut type 호출
function load_circiut_type() {
  $.getJSON("/?c=circiut_type&m=select", function (data) {
    _circiut_type = data;
  });
}

// circiut type 변경 적용
function change_circiut_type(type, form) {
  var flag = false;
  var value = $("#form_" + form + " select[name='" + type + "']").val();

  if (value != 8) flag = true;
  if (_current.disable == "1") flag = true;

  if (value != 8) {
    $("#form_" + form + " input[name='nomal_low']").val(
      _circiut_type[value].nomal_low
    );
    $("#form_" + form + " input[name='nomal_high']").val(
      _circiut_type[value].nomal_high
    );
    $("#form_" + form + " input[name='T_open_low']").val(
      _circiut_type[value].T_open_low
    );
    $("#form_" + form + " input[name='T_open_high']").val(
      _circiut_type[value].T_open_high
    );
    $("#form_" + form + " input[name='T_short_low']").val(
      _circiut_type[value].T_short_low
    );
    $("#form_" + form + " input[name='T_short_high']").val(
      _circiut_type[value].T_short_high
    );
    $("#form_" + form + " input[name='A_1_low']").val(
      _circiut_type[value].A_1_low
    );
    $("#form_" + form + " input[name='A_1_high']").val(
      _circiut_type[value].A_1_high
    );
    $("#form_" + form + " input[name='A_2_low']").val(
      _circiut_type[value].A_2_low
    );
    $("#form_" + form + " input[name='A_2_high']").val(
      _circiut_type[value].A_2_high
    );
  }

  $("#form_" + form + " input[name='nomal_low']").attr("disabled", flag);
  $("#form_" + form + " input[name='nomal_high']").attr("disabled", flag);
  $("#form_" + form + " input[name='T_open_low']").attr("disabled", flag);
  $("#form_" + form + " input[name='T_open_high']").attr("disabled", flag);
  $("#form_" + form + " input[name='T_short_low']").attr("disabled", flag);
  $("#form_" + form + " input[name='T_short_high']").attr("disabled", flag);
  $("#form_" + form + " input[name='A_1_low']").attr("disabled", flag);
  $("#form_" + form + " input[name='A_1_high']").attr("disabled", flag);
  $("#form_" + form + " input[name='A_2_low']").attr("disabled", flag);
  $("#form_" + form + " input[name='A_2_high']").attr("disabled", flag);
}

function topreload() {
  if ($.inArray("frame_main", window.frames) == -1)
    top.frame_main.location.reload();
  else top.location.reload();
}

function nullToBlank(val) {
  if (val == null || $.type(val) == "undefined") {
    return "";
  }

  return val;
}

function openHelp(name, lang) {
  // var url = "/?c=help&name=" + name + "&lang=" + lang;
  var url = "/help/" + name + "/" + lang;
  window.open(
    url,
    "help_win",
    "scrollbars=yes,toolbar=no,resizable=no,width=800,height=600"
  );
}

function dropdownEmptyToFirst(el) {
  if (el.attr("tagName").toLowerCase() != "select") return;

  if (el.val() == null) {
    el.find("option:first").attr("selected", "selected");
  }
}

// open Log Viewer
function openLogViewer(logNo, channelNo, type) {
  if (type == "1") {
    var url = "/?c=dvr_search&logNo=" + logNo + "&channelNo=" + channelNo;
    window.open(
      url,
      "dvr_search_win_" + logNo,
      "scrollbars=0, toolbar=no, resizable=yes, width=640, height=480"
    );
  } else if (type == "2") {
    var url = "/?c=nvr_search&logNo=" + logNo + "&channelNo=" + channelNo;
    window.open(
      url,
      "nvr_search_win_" + logNo,
      "scrollbars=0, toolbar=no, resizable=yes, width=640, height=480"
    );
  }
}
