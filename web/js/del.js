"use strict";

var del = function (url) {
	if (confirm("Do you want to delete?")) {
    window.location.href = url;
  }
};