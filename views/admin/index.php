<?php require '../../helpers.php' ?>
<?php loadPartial('header'); ?>

<button id="tag" class="bg-teal-400 px-3 py-2 rounded-xl text-white">Tags</button>

<?php loadPartial('footer'); ?>

<script>
    const tag = document.querySelector('#tag');
    tag.addEventListener('click', function () {
        window.location.href = "tag.php";
    })
</script>
