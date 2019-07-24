<?php

namespace Encore\Admin\Latlong\Map;

class Tencent extends AbstractMap
{
    /**
     * @var string
     */
    protected $api = '//map.qq.com/api/js?v=2.exp&key=%s';

    /**
     * {@inheritdoc}
     */
    public function applyScript(array $id)
    {
        return <<<EOT
        (function() {
            function init(name) {
                var lat = $('#{$id['lat']}');
                var lng = $('#{$id['lng']}');

                var center = new qq.maps.LatLng(lat.val(), lng.val());

                var container = document.getElementById("map_"+name);
                var map = new qq.maps.Map(container, {
                    center: center,
                    zoom: 13
                });

                var marker = new qq.maps.Marker({
                    position: center,
                    draggable: true,
                    map: map
                });

                if( ! lat.val() || ! lng.val()) {
                    var citylocation = new qq.maps.CityService({
                        complete : function(result){
                        map.setCenter(result.detail.latLng);
                        marker.setPosition(result.detail.latLng);
                    }
                    });

                    citylocation.searchLocalCity();
                }

                qq.maps.event.addListener(map, 'click', function(event) {
                    marker.setPosition(event.latLng);
                });

                qq.maps.event.addListener(marker, 'position_changed', function(event) {
                    var position = marker.getPosition();
                    lat.val(position.getLat());
                    lng.val(position.getLng());
                });
            }

            init('{$id['lat']}{$id['lng']}');
        })();
EOT;
    }
}