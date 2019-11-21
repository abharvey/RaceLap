/* REACT LOADING */
"use strict";

const participantsByCategory = (category, participants = []) =>
  participants.filter(p => category.includes(p.cat));

const detailTitles = {
  organizer: "Race Series",
  date: "Date",
  commissaire: "Commissaire",
  participant: "Participants",
  resultstatus: "Status"
};

const detailsInOrder = [
  "organizer",
  "date",
  "resultstatus",
  "participant",
  "commissaire"
];

const EventDetails = ({ eventDetails }) =>
  React.createElement(
    "ul",
    null,
    detailsInOrder.map(key =>
      React.createElement(
        "li",
        {
          key: `${key}-${eventDetails[key]}`,
          style: { listStyleType: "none" }
        },
        `${detailTitles[key]}: ${eventDetails[key]}`
      )
    )
  );

const ParticipantRow = props => {
  return null;
};

const CategoryTable = props => {
  return React.createElement("");
};

const EventHeader = ({ name, location }) =>
  React.createElement("h1", { className: "event-header" }, [
    React.createElement("span", { key: "name-header" }, `${name} `),
    React.createElement("small", { key: "location-header" }, location),
    React.createElement(
      "button",
      { key: "details-btn", className: "details-btn" },
      "Detailed Results"
    )
  ]);

class EventPage extends React.Component {
  constructor(props) {
    super(props);

    this.handleDataLoad = this.handleDataLoad.bind(this);

    window.addEventListener("event::data::loaded", this.handleDataLoad);

    this.state = {
      event: {},
      categories: [],
      participants: [{}]
    };
  }

  handleDataLoad(e) {
    const event = e.detail.eventData.event[0];

    const categories = Object.keys(e.detail.eventData).filter(
      key => key !== "event"
    );

    const participants = categories.reduce((categorizedRiders, cat) => {
      categorizedRiders[cat] = e.detail.eventData[cat].results;
      return categorizedRiders;
    }, {});

    this.setState({
      event,
      categories,
      participants
    });
  }

  render() {
    const {
      event,
      event: { name, location }
    } = this.state;

    return React.createElement("div", null, [
      React.createElement(EventHeader, { name, location, key: "header-key" }),
      React.createElement(EventDetails, {
        eventDetails: this.state.event,
        key: "details-key"
      })
    ]);
  }
}

window.onload = function() {
  ReactDOM.render(
    React.createElement(EventPage, {}),
    document.getElementById("react-root")
  );
};
