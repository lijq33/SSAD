<template>
    <div>
        <div class = "tw-flex">
            <div class="col-md-10">
                <!-- search -->
                <div class="tw-w-full tw-flex tw-mb-2">
                    <label
                        for="search"
                        class="col-md-4 col-form-label"
                    >
                        Search:
                    </label>
                    <div class="col-md-5">
                        <div>
                            <GmapAutocomplete
                                class="tw-border-grey tw-border-2 tw-rounded tw-p-2 tw-w-64"
                                @place_changed="setPlace"
                                ref="autocomplete"
                            ></GmapAutocomplete>
                        </div>
                    </div>
                </div>

                <!-- Address -->
                <div class="tw-w-full tw-flex">
                    <label
                        for="Address"
                        class="col-md-4 col-form-label"
                    >
                        Address:
                    </label>
                    <div class="col-md-5">

                        <div v-if="!isLoading">
                            <input 
                                class = "tw-border-grey-light tw-bg-grey-light tw-border-2 tw-rounded tw-p-2 tw-w-64"
                                type='text'
                                v-model="form.address"
                                placeholder=""
                                disabled
                                style="resize:none"
                            />
                        </div>
                        <div v-else>
                            <img
                                src="/assets/img/loader.gif"
                                alt="Loading..."
                            >
                        </div>

                    </div>
                </div>

            </div>
            <div v-if="!isLoading" class="col-md-5">
                <b-button
                    v-if="form.address != '' && currentUser.roles == 'CallCenterOperator'"
                    variant="primary"
                    @click="confirmAddressBtn"
                >Confirm</b-button>

            </div>
        </div>
        <!--just a postal code helper without map reference -->
        <div id="service-helper"></div>
    </div>
</template>

<script>
export default {
    props: ["queryFullAddress", "clearSearchResultValue"],

    data() {
        return {
            confirmAddress: { full_address: null, postal_code: null },
            tempPostalCode: null,
            isLoading: false,
            tempFullAddres: "",
            form: {
                address: ""
            }
        };
    },
    methods: {
        confirmAddressBtn() {
            this.$emit("confirm-address", this.confirmAddress);
        },
        retrieveAddressFromBackEnd(postalCode, googleLatLng) {
            var scope = this;

            axios
                .get("/api/address/postal_code/" + postalCode + ".json")
                .then(res => {
                    console.log(res.data);

                    //append google latlng
                    res.data.lat = googleLatLng.lat;
                    res.data.lng = googleLatLng.lng;

                    this.$emit("get-search-data", res.data);
                    this.form.address = res.data.full_address;
                    this.confirmAddress = res.data;

                    //use google address
                    if (res.data.full_address === undefined) {
                        scope.form.address = scope.tempFullAddres;
                        this.confirmAddress["full_address"] =
                            scope.tempFullAddres;
                        this.confirmAddress[
                            "postal_code"
                        ] = this.tempPostalCode;
                    }

                    scope.isLoading = false;
                })
                .catch(error => {
                    console.log(error);
                })
                .then(() => {});
        },
        bestAddressMatch(
            geocoder,
            pos,
            serviceFormatedAddress,
            matchPostalCode
        ) {
            var scope = this;
            var foundBestMatch = false;

            geocoder.geocode({ location: pos }, function(results, status) {
                if (status === "OK") {
                    results.forEach(element => {
                        if (
                            element.formatted_address.includes(matchPostalCode)
                        ) {
                            foundBestMatch = true;
                            if (
                                element.formatted_address.length >
                                serviceFormatedAddress.length
                            ) {
                                scope.form.address = element.formatted_address;
                                scope.confirmAddress.full_address =
                                    element.formatted_address;
                                scope.confirmAddress.postal_code = matchPostalCode;
                            } else {
                                scope.form.address = serviceFormatedAddress;
                                scope.confirmAddress.full_address = serviceFormatedAddress;
                            }
                        }
                    });

                    if (!foundBestMatch) {
                        scope.form.address = serviceFormatedAddress;
                        scope.confirmAddress.full_address = serviceFormatedAddress;
                        scope.form.address = "";
                    }
                }
            });
        },
        setPlace(place) {
            var scope = this;

            if (place.id) {
                var matchPostalCode;
                place.address_components.forEach(address_component => {
                    if (address_component.types[0] == "postal_code") {
                        matchPostalCode = address_component.long_name;
                        scope.form.postalCode = address_component.short_name;
                    }
                });

                var pos = {
                    lat: place.geometry.location.lat(),
                    lng: place.geometry.location.lng()
                };

                this.$emit("get-search-data", pos);

                var service = new google.maps.places.PlacesService(
                    $("#service-helper").get(0)
                );

                service.getDetails({ placeId: place.place_id }, function(
                    place,
                    status
                ) {
                    if (status === google.maps.places.PlacesServiceStatus.OK) {
                        scope.bestAddressMatch(
                            new google.maps.Geocoder(),
                            pos,
                            place.formatted_address,
                            matchPostalCode
                        );
                    }
                });
            }
        },
        geoCodeAddress(pos) {
            var scope = this;
            scope.isLoading = true;
            new google.maps.Geocoder().geocode({ location: pos }, function(
                results,
                status
            ) {
                if (status === "OK") {
                    //google formatted address
                    scope.tempFullAddres = results[0].formatted_address;

                    //get postal code from best result
                    var postalCode =
                        results[0].address_components[
                            results[0].address_components.length - 1
                        ].long_name;
                    scope.tempPostalCode = postalCode;

                    if (postalCode == "Singapore") {
                        //no postal code available
                        console.log("no postal code found");
                        scope.form.address = "";
                        scope.isLoading = false;
                    } else {
                        console.log(results[0].formatted_address);
                        scope.form.postalCode = postalCode;

                        scope.retrieveAddressFromBackEnd(postalCode, pos);
                    }
                }
            });
        }
    },
    watch: {
        queryFullAddress(newValue) {
            this.geoCodeAddress(newValue);
        },
        clearSearchResultValue(newValue) {
            this.isLoading = false;
            this.form.address = "";
            this.$refs.autocomplete.$el.value = "";
            this.confirmAddress.full_address = null;
            this.confirmAddress.postal_code = null;
        }
    },
    computed: {
        currentUser() {
            return this.$store.getters.currentUser;
        },

        isCallCenterOperator() {
            if (!this.currentUser) return false;
            return this.currentUser.roles == "CallCenterOperator";
        },

        isCrisisManager() {
            if (!this.currentUser) return false;
            return this.currentUser.roles == "CrisisManager";
        },

        isAccountManager() {
            if (!this.currentUser) return false;
            return this.currentUser.roles == "AccountManager";
        }
    }
};
</script>

<style>
.pac-container.pac-logo {
    z-index: 999999;
}
.pac-container {
    z-index: 999999 !important;
}
</style>