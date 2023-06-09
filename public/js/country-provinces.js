    function updateCountryProvinces(province, countryCode) {
        $(province).children('optgroup').remove();
        $(province).data('options').each(function(){
            if ($(this).data('country_code') == countryCode) {
                $(province).append(this);
            }
        })
    }
    /*
     * @province <select> element with opgtroups of provinces separated by country
     * @country  <select> element
     */
    function countryProvinces(province, country)
    {
        //zf2 select options dont allow non-standard attributes.. hooray.
        opts = $(province).children('optgroup').each(function(){
            $(this).attr('data-country_code', $(this).children().val().substring(0,2));
        });
        $(province).data('options', opts);

        //on change of country, update province element to list provinces that match the country
        $(country).on('change', function() {
            updateCountryProvinces(province, $(country).val());
        });

        selectedProvince = $(province).val();
        updateCountryProvinces(province, $(country).val());
        $(province).val(selectedProvince);
    }










    function updateProvinceCities(province, cityCode) {
        $(province).children('optgroup').remove();
        $(province).data('options').each(function () {
            if ($(this).data('city_code') == cityCode) {
                $(province).append(this);
            }
        })
    }
    /*
     * @province <select> element with opgtroups of provinces separated by country
     * @country  <select> element
     */
    function provinceCities(city, province) {
        //zf2 select options dont allow non-standard attributes.. hooray.
        opts = $(city).children('optgroup').each(function () {
            $(this).attr('data-country_code', $(this).children().val().substring(0, 2));
        });
        $(city).data('options', opts);

        //on change of country, update province element to list provinces that match the country
        $(country).on('change', function () {
            updateCountryProvinces(province, $(province).val());
        });

        selectedProvince = $(city).val();
        updateProvinceCities(city, $(province).val());
        $(city).val(selectedProvince);
    }
