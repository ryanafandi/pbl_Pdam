<tr class="rab-row">
  <td>
    <select name="kategori[]" class="form-control form-control-sm rab-kategori">
      <option value="pipa_dinas" {{ $kategoriVal === 'pipa_dinas' ? 'selected' : '' }}>Pipa Dinas</option>
      <option value="pipa_persil" {{ $kategoriVal === 'pipa_persil' ? 'selected' : '' }}>Pipa Persil</option>
    </select>
  </td>
  <td>
    <input type="text" name="uraian[]" class="form-control form-control-sm"
           value="{{ $uraianVal }}" placeholder="Uraian pekerjaan / material">
  </td>
  <td>
    <input type="text" name="satuan[]" class="form-control form-control-sm"
           value="{{ $satuanVal }}" placeholder="m / pcs / unit">
  </td>
  <td>
    <input type="number" step="1" min="0"
           name="volume[]" class="form-control form-control-sm rab-volume"
           value="{{ $volumeVal }}">
  </td>
  <td>
    <input type="number" step="1" min="0"
           name="harga_satuan[]" class="form-control form-control-sm rab-harga"
           value="{{ $hargaVal }}">
  </td>
  <td>
    <input type="text" class="form-control form-control-sm rab-jumlah" readonly>
  </td>
  <td class="text-center">
    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeRabRow(this)">
      <i class="fas fa-times"></i>
    </button>
  </td>
</tr>
