const onDeleteButtonClick = () => {
  $('.delete-btn').click(function () {
    $(this).closest('.form-group.row').remove();
  });
};

onDeleteButtonClick();

$('.add-image-btn').click(function () {
  const index = +$('#widgets-counter').val();

  $('#widgets-counter').val(index + 1);

  console.log(index);

  const template = $('#ad_images')
    .data('prototype')
    .replace(/__name__/g, index);

  $('#ad_images').append(template);

  onDeleteButtonClick();
});

const setWidgetsCounter = () => {
  const counter = $('#ad_images > .form-group').length;

  $('#widgets-counter').val(counter);
};

setWidgetsCounter();
