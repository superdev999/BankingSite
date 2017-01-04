var allBanken = window.banken;
var allProdukte = window.produkte;
var statusDiv = document.getElementById('import-status');
var logDiv = document.getElementById('import-log');

jQuery('.select-all').click(function() {
  var type = $(this).attr('data-type');
  $('.'+type+'-cb').attr('checked', true);
  return false;
});

jQuery('.select-none').click(function() {
  var type = $(this).attr('data-type');
  $('.'+type+'-cb').attr('checked', false);
  return false;
});

jQuery('#start-import').click(function() {
  var banken = allBanken.filter(function(node) {return $('.bank-cb[data-nid=' + node.nid + ']').attr('checked'); });
  var produkte = allProdukte.filter(function(node) {return $('.produkt-cb[data-nid=' + node.nid + ']').attr('checked'); });
  var from = $('[name=from]').val();
  var toOrg = $('[name=to]').val();
  var button = $(this);
  button.text('Neu Starten');
  button.hide();
  logDiv.innerHTML = '';
  var i = 0;
  statusDiv.innerHTML = 'Bank: ' + i + '/' + banken.length;
  var imported = 0;
  var total = 0;
  var importFeedbackForBank = function(to) {
    if(i >= banken.length) {
      i = 0;
      importFeedbackForProdukt();
      return;
    }
    var node = banken[i];
    var fromBank = (node.from ? node.from.substring(0,10) : from);
    var toBank = (node.to ? node.to.substring(0,10) : toOrg);
    if(typeof to === "string") {
      toBank = to;
    }

    if(total === 0) {
      logDiv.innerHTML += '<br>' + node.title + ": " ;
    }
    return $.ajax({
      url: "/ekomiimportdobank",
      data: {
        bankId: node.nid,
        shopId: node.field_ekomishopid_value,
        from: fromBank,
        to: toBank
      },
      success: function(data) {
      data = JSON.parse(data);
      imported += data.imported;
      total += data.total;
      if(data.total !== 0 && data.lastDate) {
        return importFeedbackForBank(data.lastDate);
      }
      i++;
      logDiv.innerHTML +=  imported + ' von ' + total + " Bewertungen importiert";
      imported = 0;
      total = 0;
      if(i < banken.length) {
        statusDiv.innerHTML = 'Bank: ' + i + '/' + banken.length;
        importFeedbackForBank();
      } else {
        i = 0;
        importFeedbackForProdukt();
      }
      },
      error: function(xhr, textStatus, errorThrown) {
        statusDiv.innerHTML = 'Fehler: ' + textStatus + " " + errorThrown + " " + xhr.responseText;
        button.show();
      }
    });
  };

  var importFeedbackForProdukt = function(to) {
    if(i >= produkte.length) {
      statusDiv.innerHTML = 'Fertig<br><br>';
      button.show();
      return;
    }
    var node = produkte[i];
    var fromProdukt = (node.from ? node.from.substring(0,10) : from);
    var toProdukt = (node.to ? node.to.substring(0,10) : toOrg);
    if(typeof to === "string") {
      toProdukt = to;
    }

    if(total === 0) {
      logDiv.innerHTML += '<br>' + node.title + ": " ;
    }
    return $.ajax({
      url: "/ekomiimportdoprodukt",
      data: {
        nodeId: node.nid,
        shopId: node.field_ekomishopid_value,
        productId: node.field_ekomiproductid_value,
        from: fromProdukt,
        to: toProdukt
      },
      success: function(data) {
      data = JSON.parse(data);
      imported += data.imported;
      total += data.total;
      if(data.total !== 0 && data.lastDate) {
        return importFeedbackForProdukt(data.lastDate);
      }
      i++;
      logDiv.innerHTML +=  imported + ' von ' + total + " Bewertungen importiert";
      imported = 0;
      total = 0;
      if(i < banken.length) {
        statusDiv.innerHTML = 'Produkt: ' + i + '/' + produkte.length;
        importFeedbackForProdukt();
      } else {
        statusDiv.innerHTML = 'Fertig<br><br>';
        button.show();
      }
      },
      error: function(xhr, textStatus, errorThrown) {
        statusDiv.innerHTML = 'Fehler: ' + textStatus + " " + errorThrown + " " + xhr.responseText;
        button.show();
      }
    });
  };

  importFeedbackForBank();
  return false;
});
