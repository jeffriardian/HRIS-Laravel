import reportInterrogation from "./states/interrogation-report";
import reportIncidentPenalty from "./states/incident-penalty";
import reportMemorandum from "./states/memorandum";

export default {
    modules: {
        reportIncidentPenalty,
        reportInterrogation,
        reportMemorandum
    }
};
