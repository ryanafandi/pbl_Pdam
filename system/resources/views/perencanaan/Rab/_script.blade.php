<script>
  function recalcRab() {
    let subtotalDinas  = 0;
    let subtotalPersil = 0;

    document.querySelectorAll('#rabTable tbody tr.rab-row').forEach(function (row) {
      const kat   = row.querySelector('.rab-kategori')?.value || 'pipa_dinas';
      const vol   = parseFloat(row.querySelector('.rab-volume')?.value || 0);
      const harga = parseFloat(row.querySelector('.rab-harga')?.value || 0);
      const jml   = vol * harga;

      const jumlahInput = row.querySelector('.rab-jumlah');
      if (jumlahInput) {
        jumlahInput.value = jml.toLocaleString('id-ID');
      }

      if (kat === 'pipa_pensil') {
        subtotalPersil += jml;
      } else {
        subtotalDinas += jml;
      }
    });

    const biayaPend = parseFloat(document.querySelector('input[name="biaya_pendaftaran"]')?.value || 0);
    const biayaAdm  = parseFloat(document.querySelector('input[name="biaya_admin"]')?.value || 0);
    const total     = subtotalDinas + subtotalPersil + biayaPend + biayaAdm;

    document.getElementById('subtotalDinas').innerText  = subtotalDinas.toLocaleString('id-ID');
    document.getElementById('subtotalPersil').innerText = subtotalPersil.toLocaleString('id-ID');
    document.getElementById('totalRab').innerText       = total.toLocaleString('id-ID');
  }

  function addRabRow() {
    const tbody = document.querySelector('#rabTable tbody');
    if (!tbody) return;

    const tpl = `@php
      // render satu baris default sebagai template string
      ob_start();
      echo view('perencanaan.rab._row', [
        'kategoriVal' => 'pipa_dinas',
        'uraianVal'   => '',
        'satuanVal'   => '',
        'volumeVal'   => 0,
        'hargaVal'    => 0,
      ])->render();
      $html = trim(preg_replace('/\s+/', ' ', ob_get_clean()));
      echo str_replace('`','\\`',$html);
    @endphp`;

    tbody.insertAdjacentHTML('beforeend', tpl);
    recalcRab();
  }

  function removeRabRow(btn) {
    const row = btn.closest('tr');
    const tbody = row?.parentElement;
    if (!row || !tbody) return;

    if (tbody.querySelectorAll('tr.rab-row').length <= 1) {
      // minimal 1 baris
      row.querySelectorAll('input').forEach(i => i.value = '');
      return;
    }

    row.remove();
    recalcRab();
  }

  document.addEventListener('input', function (e) {
    if (
      e.target.matches('.rab-volume') ||
      e.target.matches('.rab-harga') ||
      e.target.name === 'biaya_pendaftaran' ||
      e.target.name === 'biaya_admin' ||
      e.target.matches('.rab-kategori')
    ) {
      recalcRab();
    }
  });

  document.addEventListener('DOMContentLoaded', recalcRab);
</script>
