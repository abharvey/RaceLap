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

const removeResults = [];

//TODO: use a stateful "detailed results" to add a column filter
const ParticipantRow = props => {
  return React.createElement("tr", { className: "table-row" }, [
    props.columnHeaders.map(header =>
      React.createElement(
        "td",
        { key: header, className: "table-cell" },
        `${props.rider[header]}`
      )
    )
  ]);
};

const HeaderRow = props => {
  return React.createElement(
    "thead",
    null,
    React.createElement("tr", { className: "table-row" }, [
      props.columnHeaders.map(header =>
        React.createElement("th", { className: "table-cell" }, header)
      )
    ])
  );
};

const CategoryTable = props => {
  const tableHeaders = props.cat.split("::");
  const columnHeaders = Object.keys(props.riders[0] || {});
  console.log(columnHeaders);

  return React.createElement("div", { className: "category-table" }, [
    React.createElement(
      "h3",
      { key: "cat-header", className: "category-header" },
      [
        React.createElement(
          "span",
          { key: "category-name" },
          `${tableHeaders[0]} `
        ),
        React.createElement(
          "small",
          { key: "cat-sub-name" },
          `${tableHeaders[1]} `
        )
      ]
    ),
    React.createElement("table", { key: "cat-table" }, [
      React.createElement(HeaderRow, { columnHeaders, key: "header-row" }),
      React.createElement(
        "tbody",
        { key: "table-body" },
        props.riders.map(rider =>
          React.createElement(ParticipantRow, {
            columnHeaders,
            rider,
            key: rider["Place"]
          })
        )
      )
    ])
  ]);
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
      participants: {}
    };
  }

  handleDataLoad(e) {
    const data = { ...e.detail.eventData };
    const event = { ...e.detail.eventData.event[0] };

    const categories = Object.keys(data).filter(key => key !== "event");

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
      categories,
      event,
      event: { name, location },
      participants
    } = this.state;

    return React.createElement("div", null, [
      React.createElement(EventHeader, { name, location, key: "header-key" }),
      React.createElement(EventDetails, {
        eventDetails: this.state.event,
        key: "details-key"
      }),
      ...categories.map(cat =>
        React.createElement(CategoryTable, {
          cat,
          key: cat,
          riders: participants[cat]
        })
      )
    ]);
  }
}

window.onload = function() {
  ReactDOM.render(
    React.createElement(EventPage, {}),
    document.getElementById("react-root")
  );
};
