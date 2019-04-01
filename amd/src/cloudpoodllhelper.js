define(["jquery", "core/log", "qtype_regexp/cloudpoodllloader"], function($, log, cloudpoodll){
    "use strict"; // jshint ;_;

    log.debug("cloudpoodll helper: initialising");

    return {

        uploadstate: false,

        init:  function(opts) {
            this.component = opts["component"];
            this.dom_id = opts["dom_id"];
            this.inputname = opts["inputname"];
            this.transcriber = opts["transcriber"];

            this.register_controls();
            this.register_events();
            this.setup_recorder();
        },

        setup_recorder: function(){
            var that = this;
            var gspeech = "";
            var recorder_callback = function(evt){
                switch(evt.type){
                    case "recording":
                        if(evt.action === "started"){
                            gspeech = "";
                           // that.controls.updatecontrol.val();
                        }
                        break;
                    case "speech":
                        gspeech += "  " + evt.capturedspeech;
                        that.controls.transcript.val(gspeech);
                        that.controls.answer.val(gspeech);
                        break;
                    case "awaitingprocessing":
                        if(that.uploadstate != "posted") {
                            that.controls.audiourl.val(evt.mediaurl);
                            if(this.transcriber=='amazon'){
                                that.controls.audiourl.val(evt.mediaurl);
                                that.controls.transcript.val(evt.transcripturl);
                                that.controls.answer.val(evt.transcripturl);
                            }
                        }
                        that.uploadstate = "posted";
                        break;
                    case "error":
                        alert("PROBLEM: " + evt.message);
                        break;
                }
            };

            cloudpoodll.init(this.dom_id, recorder_callback);
        },

        register_controls: function(){
          var name = CSS.escape(this.inputname);
          this.controls = {
            audiourl :   $("input[name=" + name + "audiourl]"),
            transcript : $("input[name=" + name + "transcript]"),
            answer :     $("input[name=" + name + "]"),
          };
        },

        register_events: function(){
            //nothing here
        }
    };//end of return object
});