class Booking {
  constructor() {
    this.setCurrentDate();
    this.setListeners();
  }

  setCurrentDate() {
    const now = new Date();
    const dates = ["startDate", "endDate"];

    dates.forEach((date) => {
      $(`#booking_${date}_year`).val(now.getFullYear());
      $(`#booking_${date}_month`).val(now.getMonth() + 1);
      $(`#booking_${date}_day`).val(now.getDate());
    });
  }

  setListeners() {
    const that = this;

    const dates = ["startDate", "endDate"];
    const parts = ["year", "month", "day"];

    dates.forEach((date) => {
      parts.forEach((part) => {
        $(`#booking_${date}_${part}`).change(function () {
          const numberOfNights = that.getNumberOfNights();

          that.showTotalPrice(numberOfNights);
        });
      });
    });
  }

  getNumberOfNights() {
    const start = this.getDate("start");
    const end = this.getDate("end");

    return Math.round((end - start) / (1000 * 60 * 60 * 24));
  }

  getDate(date) {
    const year = $(`#booking_${date}Date_year`).val();
    const month = $(`#booking_${date}Date_month`).val();
    const day = $(`#booking_${date}Date_day`).val();

    return new Date(year, month, day);
  }

  showTotalPrice(numberOfNights) {
    $("#days").text(numberOfNights);

    const pricePerNight = $("#ad-price-per-night").val();

    $("#total-price").text(pricePerNight * numberOfNights);
  }
}

new Booking();
