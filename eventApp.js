/* REACT LOADING */
const participantsByCategory = (category, participants = []) =>
  participants.filter(p => category.includes(p.cat));

const EventDetails = props => {
  const { eventDetails } = props;
  return React.createElement(
    "div",
    null,
    object
      .Keys(eventDetails)
      .filter(key => !["name", "location"].includes(key))
      .map(key =>
        React.createElement("span", null, `${key}: ${eventDetails[key]}`)
      )
  );
};

const CategoryTable = props => {
  return null;
};

class EventPage extends React.Component {
  constructor(props) {
    super(props);

    this.handleDataLoad = this.handleDataLoad.bind(this);

    window.addEventListener("event::data::loaded", this.handleDataLoad);

    this.state = {
      event: {
        name: "",
        Date: "",
        location: "",
        Commissaire: "",
        Participants: "",
        Status: "",
        "Race Series": "",
        type: ""
      },
      categories: [
        {
          title: "",
          subtitle: ""
        }
      ],
      participants: [{}]
    };
  }

  handleDataLoad(e) {
    console.log(e.detail.eventData.event);
  }

  render() {
    return React.createElement("div", null);
  }
}

window.onload = function() {
  ReactDOM.render(
    React.createElement(EventPage, {}),
    document.getElementById("react-root")
  );
};
