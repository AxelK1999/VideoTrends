<div class="mb-3 mx-4">
    <label class="form-label "><?= $title ?></label>
    <input type="<?= $type ?>" name="<?= $name ?>" class="form-control <?= $validitystatus ?>" <?= $reglas ?> >
    <div class="invalid-feedback">
        <?= $mjError ?>
    </div>
</div>