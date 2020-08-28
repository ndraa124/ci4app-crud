<div class="container mt-5">
    <div class="row">
        <div class="col">
            <h1 class="text-center">CRUD Ajax Dengan CodeIgniter 4</h1>

            <a href="javascript:void(0)" class="btn btn-primary mt-3 mb-3" onclick="tampil_form()">Tambah Data</a>
            <a href="javascript:void(0)" class="btn btn-info mt-3 mb-3" onclick="reload_table()">Reload Data</a>

            <table class="table table-striped table-hover" id="myTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Merk</th>
                        <th>Plat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" id="form" enctype="multipart/form-data">
                <input type="hidden" name="id">

                <div class="modal-body">
                    <div class="form-group row">
                        <label for="merk" class="col-sm-2 col-form-label">Merk</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="merk" name="merk">
                            <span class="help-block text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="plat" class="col-sm-2 col-form-label">Plat</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="plat" name="plat">
                            <span class="help-block text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="gambar" class="col-sm-2 col-form-label">Gambar</label>
                        <div class="col-sm-10">
                            <input type="file" class="form-control" id="gambar" name="gambar">
                            <span class="help-block text-danger"></span>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="simpan()">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var table;
    var method;
    var url;

    $(document).ready(function() {
        table = $('#myTable').DataTable({
            "pageLength": 10,
            "ajax": {
                "url": '<?= site_url('home/ajaxList'); ?>',
                "type": "GET"
            },
            "serverSide": true,
            "deferRender": true
        });
    });

    function reload_table() {
        table.ajax.reload(null, false);
    }

    function tampil_form() {
        method = 'save';

        $('#modal-form').modal('show');
        $('#modal-title').text('Tambah Data');
    }

    function simpan() {
        if (method == 'save') {
            url = '<?= site_url('home/simpan'); ?>';
        } else {
            url = '<?= site_url('home/update'); ?>';
        }

        $.ajax({
            url: url,
            type: 'POST',
            data: new FormData($('#form')[0]),
            dataType: 'JSON',
            contentType: false,
            processData: false,
            success: function(data) {
                if (data.status) {
                    $('#modal-form').modal('hide');
                    reload_table();
                } else {
                    for (var i = 0; i < data.inputerror.length; i++) {
                        $('[name="' + data.inputerror[i] + '"]').parent().addClass('has-error');
                        $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]);
                    }
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error!');
            }
        });
    }

    function editData(id) {
        method = 'update';

        $.ajax({
            url: '<?= site_url('home/edit/'); ?>' + id,
            type: 'GET',
            dataType: 'JSON',
            success: function(data) {
                $('[name="id"]').val(data.id);
                $('[name="merk"]').val(data.merk);
                $('[name="plat"]').val(data.plat);

                $('#modal-form').modal('show');
                $('#modal-title').text('Edit Data');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error!');
            }
        });
    }

    function hapusData(id) {
        if (confirm('Anda yakin ingin menghapus data ini?')) {
            $.ajax({
                url: '<?= site_url('home/delete/'); ?>' + id,
                type: 'DELETE',
                dataType: 'JSON',
                success: function(data) {
                    if (data.status) {
                        alert('Data berhasil dihapus.');
                    } else {
                        alert('Data tidak ditemukan.');
                    }

                    reload_table();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error!');
                }
            });
        }
    }
</script>