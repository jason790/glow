<?php
namespace AB\Chroma;

class Scenes implements \Iterator {
  public $scenes = [];

  public function rewind() {
    return reset($this->scenes);
  }

  public function current() {
    return current($this->scenes);
  }

  public function key() {
    return key($this->scenes);
  }

  public function next() {
    return next($this->scenes);
  }

  public function valid() {
    return key($this->scenes) !== null;
  }

  public function load() {
    $scenes_yaml = file_get_contents('scenes.yml');
    $this->_from_array(\yaml_parse($scenes_yaml));
    usort($this->scenes, array($this, '_compare_scene_names'));
  }

  public function save() {
    $scenes_yaml = yaml_emit($this->as_array());
    $fp = fopen('scenes.yml', 'w');
    fwrite($fp, $scenes_yaml);
    fclose($fp);
  }

  private function _compare_scene_names($a, $b) {
    return strcmp($a->name, $b->name);
  }

  private function _from_array($self_array) {
    foreach ($self_array as $scene) {
      $scene_id = $scene['id'];
      $this->scenes[$scene_id] = new Scene();
      $this->scenes[$scene_id]->id = $scene_id;
      $this->scenes[$scene_id]->name = $scene['name'];

      foreach ($scene['lights'] as $light) {
        $light_id = $light['id'];
        $this->scenes[$scene_id]->lights[$light_id] = new Light([
          'id'        => $light_id,
          'power'     => (bool) $light['power'],
          'colormode' => $light['colormode'],
          'ct'        => $light['ct'],
          'hue'       => $light['hue'],
          'sat'       => $light['sat'],
          'bri'       => $light['bri']
        ]);
      }
    }
  }

  public function as_array() {
    $self_array = [];

    foreach ($this->scenes as $scene_id => $scene) {
      $scene_lights = [];
      foreach ($scene->lights as $light) {
        $scene_lights[$light->id] = $light->as_array();
      }
      $self_array[$scene_id] = [
        'id'     => $scene_id,
        'name'   => $scene->name,
        'lights' => $scene_lights
      ];
    }

    return $self_array;
  }
}
